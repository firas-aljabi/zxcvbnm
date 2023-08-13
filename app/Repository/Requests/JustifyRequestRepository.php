<?php

namespace App\Repository\Requests;

use App\ApiHelper\SortParamsHelper;
use App\Filter\JustifyRequests\JustifyRequestsFilter;
use App\Models\JustifyRequest;
use App\Repository\BaseRepositoryImplementation;
use App\Statuses\JustifyStatus;
use App\Statuses\UserTypes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JustifyRequestRepository extends BaseRepositoryImplementation
{
    public function getFilterItems($filter)
    {
        $records = JustifyRequest::query()->where('user_id', Auth::id());
        if ($filter instanceof JustifyRequestsFilter) {
            $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                $sortParams = SortParamsHelper::getSortParams($filter->getOrderBy());
                if ($sortParams->getIsRelated()) {
                    $records
                        ->join(
                            $sortParams->getTable(),
                            'justify_requests.' . $sortParams->getJoinColumn(),
                            '=',
                            $sortParams->getRelation()
                        )
                        ->orderBy($sortParams->getOrderColumn(), $filter->getOrder());
                } else {
                    $records->orderBy($filter->getOrderBy(), $filter->getOrder());
                }
            });
            return $records->with('user')->paginate($filter->per_page);
        }
        return $records->with('user')->paginate($filter->per_page);
    }

    public function getFilterItemsForAdmin($filter)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {
            $records = JustifyRequest::query();
            if ($filter instanceof JustifyRequestsFilter) {


                $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                    $sortParams = SortParamsHelper::getSortParams($filter->getOrderBy());
                    if ($sortParams->getIsRelated()) {
                        $records
                            ->join(
                                $sortParams->getTable(),
                                'justify_requests.' . $sortParams->getJoinColumn(),
                                '=',
                                $sortParams->getRelation()
                            )
                            ->orderBy($sortParams->getOrderColumn(), $filter->getOrder());
                    } else {
                        $records->orderBy($filter->getOrderBy(), $filter->getOrder());
                    }
                });
                $list = $records->with('user')->paginate($filter->per_page);
                return ['success' => true, 'data' => $list];
            }
            $list = $records->with('user')->paginate($filter->per_page);
            return ['success' => true, 'data' => $list];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function add_justify_request($data)
    {

        DB::beginTransaction();

        try {
            $user_id = Auth::id();

            // Check if the user has already created a justify request on the same day
            $existing_request = JustifyRequest::where('user_id', $user_id)
                ->whereDate('start_date', $data['start_date'])
                ->whereDate('end_date', $data['end_date'])
                ->first();

            if ($existing_request) {
                return ['success' => false, 'message' => "A justify request has already been created for this user today."];
            }

            $justifyRequest = new JustifyRequest();
            $justifyRequest->user_id =  $user_id;
            $justifyRequest->reason = $data['reason'];
            $justifyRequest->type = $data['type'];
            $justifyRequest->start_date = $data['start_date'];
            $justifyRequest->end_date = $data['end_date'];
            $justifyRequest->status = JustifyStatus::PENDING;
            $justifyRequest->company_id = auth()->user()->company_id;

            if (Arr::has($data, 'medical_report_file')) {
                $file = Arr::get($data, 'medical_report_file');
                $extention = $file->getClientOriginalExtension();
                $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                $file->move(public_path('uploaded'), $file_name);

                $image_file_path = public_path('uploaded/' . $file_name);
                $image_data = file_get_contents($image_file_path);
                $base64_image = base64_encode($image_data);
                $justifyRequest->medical_report_file = $base64_image;
            }


            $justifyRequest->save();

            DB::commit();

            if ($justifyRequest === null) {
                return ['success' => false, 'message' => "Justify Request was not created"];
            }

            return ['success' => true, 'data' => $justifyRequest->load(['user'])];
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());

            throw $e;
        }
    }

    public function reject_justify_request($data)
    {
        DB::beginTransaction();
        try {
            $justiRequest = $this->getById($data['justy_request_id']);
            if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type = UserTypes::HR && auth()->user()->company_id == $justiRequest->company_id) {

                $justiRequest->update([
                    'status' => JustifyStatus::REJECTED,
                    'reject_reason' => $data['reject_reason']
                ]);

                DB::commit();
                if ($justiRequest === null) {
                    return ['success' => false, 'message' => "Justify Request was not Updated"];
                }
                return ['success' => true, 'data' => $justiRequest->load('user')];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function model()
    {
        return JustifyRequest::class;
    }
}
