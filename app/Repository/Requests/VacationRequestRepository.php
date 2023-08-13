<?php

namespace App\Repository\Requests;

use App\ApiHelper\SortParamsHelper;
use App\Filter\VacationRequests\MonthlyShiftFilter;
use App\Filter\VacationRequests\RequestFilter;
use App\Filter\VacationRequests\VacationRequestFilter;
use App\Models\Attendance;
use App\Models\EmployeeAvailableTime;
use App\Models\EmployeeRequest;
use App\Models\JustifyRequest;
use App\Models\VacationRequest;
use App\Repository\BaseRepositoryImplementation;
use App\Statuses\EmployeeRequestsType;
use App\Statuses\PaymentType;
use App\Statuses\UserTypes;
use App\Statuses\VacationRequestStatus;
use App\Statuses\VacationRequestTypes;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VacationRequestRepository extends BaseRepositoryImplementation
{

    public function add_vacation_request($data)
    {
        DB::beginTransaction();

        try {
            $user_id = Auth::id();
            $current_date = date('Y-m-d');
            $existing_request = VacationRequest::where('user_id', $user_id)
                ->whereDate('created_at', $current_date)
                ->first();
            if ($existing_request) {
                return ['success' => false, 'message' => "A Vacation request has already been created for this user today,Please Contact With Hr."];
            }
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];


            $availableTime = EmployeeAvailableTime::where('user_id', auth()->user()->id)->first();
            if (!$availableTime) {
                return ['success' => false, 'message' => "You Cannot Request Vacation Because You Don't Have Accrued Vacation Hours."];
            }
            $availableTimeHoursCount =  $availableTime->hours_daily;
            $availableDaysMonthlyCount =  $availableTime->days_monthly;
            $availableDaysAnnualCount =  $availableTime->days_annual;

            if ($data['type'] == VacationRequestTypes::HOURLY && $data['payment_type'] == PaymentType::PAYMENT) {
                $existing_vacation_time = VacationRequest::where('start_date', $start_date)
                    ->where('end_date', $end_date)
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '<=', $end_time)
                            ->where('end_time', '>=', $start_time);
                    })->first();
                if ($existing_vacation_time) {
                    return ['success' => false, 'message' => "You Cannot request a vacation in this Time, Please Choose Another Time."];
                }

                $start = new DateTime($start_time);
                $end = new DateTime($end_time);
                $diff = $start->diff($end);
                $hours = $diff->format('%h.%i');

                if ($availableTimeHoursCount != 0 && $hours <  $availableTimeHoursCount) {
                    $vacationRequest = new VacationRequest();
                    $vacationRequest->user_id = $user_id;
                    $vacationRequest->reason = $data['reason'];
                    $vacationRequest->type = $data['type'];
                    $vacationRequest->status = VacationRequestStatus::PENDING;
                    $vacationRequest->start_date = $data['start_date'];
                    $vacationRequest->end_date = $data['end_date'];
                    $vacationRequest->start_time = $data['start_time'];
                    $vacationRequest->end_time = $data['end_time'];
                    $vacationRequest->payment_type = $data['payment_type'];
                    $vacationRequest->company_id = auth()->user()->company_id;
                    $vacationRequest->save();

                    $availableTime->update([
                        'hours_daily' => $availableTime->hours_daily - $hours
                    ]);
                } else {
                    return ['success' => false, 'message' => "You Cannot request a vacation Because Your Available Hours Ended."];
                }
            } elseif ($data['type'] == VacationRequestTypes::DAILY && $data['payment_type'] == PaymentType::PAYMENT) {

                $existing_vacation_time = VacationRequest::where('start_date', $start_date)
                    ->where('end_date', $end_date)
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '<=', $end_time)
                            ->where('end_time', '>=', $start_time);
                    })->first();
                if ($existing_vacation_time) {
                    return ['success' => false, 'message' => "You Cannot request a vacation in this Time, Please Choose Another Time."];
                }

                $start = new DateTime($start_date);
                $end = new DateTime($end_date);
                $diff = $start->diff($end);
                $days = $diff->days;

                if ($availableDaysMonthlyCount != 0 && $days <  $availableDaysMonthlyCount) {
                    $vacationRequest = new VacationRequest();
                    $vacationRequest->user_id = $user_id;
                    $vacationRequest->reason = $data['reason'];
                    $vacationRequest->type = $data['type'];
                    $vacationRequest->status = VacationRequestStatus::PENDING;
                    $vacationRequest->start_date = $data['start_date'];
                    $vacationRequest->end_date = $data['end_date'];
                    $vacationRequest->payment_type = $data['payment_type'];
                    $vacationRequest->company_id = auth()->user()->company_id;
                    $vacationRequest->save();

                    $availableTime->update([
                        'days_monthly' => $availableTime->days_monthly - $days
                    ]);
                } else {
                    return ['success' => false, 'message' => "You Cannot request a vacation Because Your Available Hours Ended."];
                }
            } elseif ($data['type'] == VacationRequestTypes::ANNUL && $data['payment_type'] == PaymentType::PAYMENT) {

                $existing_vacation_time = VacationRequest::where('start_date', $start_date)
                    ->where('end_date', $end_date)
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '<=', $end_time)
                            ->where('end_time', '>=', $start_time);
                    })->first();
                if ($existing_vacation_time) {
                    return ['success' => false, 'message' => "You Cannot request a vacation in this Time, Please Choose Another Time."];
                }

                $start = new DateTime($start_date);
                $end = new DateTime($end_date);
                $diff = $start->diff($end);
                $daysAnnual = $diff->days;

                if ($availableDaysAnnualCount != 0 && $daysAnnual <  $availableDaysAnnualCount) {
                    $vacationRequest = new VacationRequest();
                    $vacationRequest->user_id = $user_id;
                    $vacationRequest->reason = $data['reason'];
                    $vacationRequest->type = $data['type'];
                    $vacationRequest->status = VacationRequestStatus::PENDING;
                    $vacationRequest->start_date = $data['start_date'];
                    $vacationRequest->end_date = $data['end_date'];
                    $vacationRequest->payment_type = $data['payment_type'];
                    $vacationRequest->company_id = auth()->user()->company_id;
                    $vacationRequest->save();

                    $availableTime->update([
                        'days_annual' => $availableTime->days_annual - $daysAnnual
                    ]);
                } else {
                    return ['success' => false, 'message' => "You Cannot request a vacation Because Your Available Hours Ended."];
                }
            } elseif ($data['type'] == VacationRequestTypes::METERNITY && auth()->user()->gender == " male") {
                return ['success' => false, 'message' => "You Cannot request a vacation For This Reason."];
            } else {
                $existing_vacation = VacationRequest::where('start_date', $start_date)
                    ->orWhere('end_date', $end_date)
                    ->whereBetween('start_date', [$start_date, $end_date])
                    ->whereBetween('end_date', [$start_date, $end_date])
                    ->orWhere(function ($query) use ($start_date, $end_date) {
                        $query->where('start_date', '<=', $end_date)
                            ->where('end_date', '>=', $start_date);
                    })->first();



                $vacationRequest = new VacationRequest();
                $vacationRequest->user_id = $user_id;
                $vacationRequest->reason = $data['reason'];
                $vacationRequest->type = $data['type'];
                $vacationRequest->status = VacationRequestStatus::PENDING;
                $vacationRequest->start_date = $data['start_date'];
                $vacationRequest->end_date = $data['end_date'];
                $vacationRequest->payment_type = $data['payment_type'];
                $vacationRequest->company_id = auth()->user()->company_id;

                $vacationRequest->save();
                if ($existing_vacation) {
                    return ['success' => false, 'message' => "You Cannot request a vacation in this Time, Please Choose Another Date."];
                }
            }
            DB::commit();
            if ($vacationRequest === null) {
                return ['success' => false, 'message' => "Vacation Request was not created"];
            }

            return ['success' => true, 'data' => $vacationRequest->load(['user'])];
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());

            throw $e;
        }
    }

    public function reject_vacation_request($data)
    {

        DB::beginTransaction();
        try {
            $vacationRequest = $this->getById($data['vacation_request_id']);
            if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type = UserTypes::HR && auth()->user()->company_id == $vacationRequest->company_id) {

                $vacationRequest->update([
                    'status' => VacationRequestStatus::REJECTED,
                    'reject_reason' => $data['reject_reason']
                ]);

                DB::commit();
                if ($vacationRequest === null) {
                    return ['success' => false, 'message' => "Vacation Request was not Updated"];
                }
                return ['success' => true, 'data' => $vacationRequest->load('user')];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function getFilterItems($filter)
    {
        $records = VacationRequest::query()->where('user_id', Auth::id());
        if ($filter instanceof VacationRequestFilter) {

            $records->when(isset($filter->duration), function ($records) use ($filter) {
                $duration_filter = $filter->duration;

                if ($duration_filter == 1) {
                    $records->where(function ($query) {
                        $query->where('type', VacationRequestTypes::HOURLY);
                    });
                } else if ($duration_filter == 2) {
                    $records->where(function ($query) {
                        $query->where('type', VacationRequestTypes::DAILY)
                            ->orWhere('type', VacationRequestTypes::ANNUL);
                    });
                }
            });

            $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                $sortParams = SortParamsHelper::getSortParams($filter->getOrderBy());
                if ($sortParams->getIsRelated()) {
                    $records
                        ->join(
                            $sortParams->getTable(),
                            'vacation_requests.' . $sortParams->getJoinColumn(),
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
            $records = VacationRequest::query();
            if ($filter instanceof VacationRequestFilter) {

                $records->when(isset($filter->duration), function ($records) use ($filter) {
                    $duration_filter = $filter->duration;

                    if ($duration_filter == 1) {
                        $records->where(function ($query) {
                            $query->where('type', VacationRequestTypes::HOURLY);
                        });
                    } else if ($duration_filter == 2) {
                        $records->where(function ($query) {
                            $query->where('type', VacationRequestTypes::DAILY)
                                ->orWhere('type', VacationRequestTypes::ANNUL);
                        });
                    }
                });

                $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                    $sortParams = SortParamsHelper::getSortParams($filter->getOrderBy());
                    if ($sortParams->getIsRelated()) {
                        $records
                            ->join(
                                $sortParams->getTable(),
                                'vacation_requests.' . $sortParams->getJoinColumn(),
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


    public function getMonthlyShiftList($filter)
    {
        if (Attendance::where('user_id', Auth::id())) {
            $records = Attendance::query();
            if ($filter instanceof MonthlyShiftFilter) {
                $records->where('user_id', Auth::id());

                $records->when(isset($filter->orderBy), function ($records) use ($filter) {
                    $sortParams = SortParamsHelper::getSortParams($filter->getOrderBy());
                    if ($sortParams->getIsRelated()) {
                        $records
                            ->join(
                                $sortParams->getTable(),
                                'attendances.' . $sortParams->getJoinColumn(),
                                '=',
                                $sortParams->getRelation()
                            )
                            ->orderBy($sortParams->getOrderColumn(), $filter->getOrder());
                    } else {
                        $records->orderBy($filter->getOrderBy(), $filter->getOrder());
                    }
                });
                $list = $records->paginate($filter->per_page);
                return ['success' => true, 'data' => $list];
            }
            $list = $records->paginate($filter->per_page);
            return ['success' => true, 'data' => $list];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }


    public function getRequests(RequestFilter $requestFilter = null)
    {

        $vacationRequests = VacationRequest::select(
            'id',
            'user_id',
            'type',
            'status',
            'start_date',
            'end_date',
            'reason',
            DB::raw("1 as is_vacation")
        )
            ->where('company_id', auth()->user()->company_id)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();


        $justifyRequests = JustifyRequest::select(
            'id',
            'user_id',
            'type',
            'reason',
            'start_date',
            'end_date',
            'medical_report_file',
            DB::raw("2 as is_justify")
        )
            ->where('company_id', auth()->user()->company_id)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();

        $resignationRequests = EmployeeRequest::select(
            'id',
            'user_id',
            'type',
            'reason',
            'attachments',
            DB::raw("3 as is_resignation_request")
        )->where('company_id', auth()->user()->company_id)
            ->where('type', EmployeeRequestsType::RESIGNATION)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();

        $retrementRequests = EmployeeRequest::select(
            'id',
            'user_id',
            'type',
            'reason',
            'attachments',
            DB::raw("4 as is_retrement_request")
        )->where('company_id', auth()->user()->company_id)
            ->where('type', EmployeeRequestsType::RETIREMENT)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();

        $allRequests = $vacationRequests->concat($justifyRequests)->concat($resignationRequests)->concat($retrementRequests);

        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR) {
            if ($requestFilter != null && $requestFilter->request_type == 1) {
                return $vacationRequests;
            } elseif ($requestFilter != null && $requestFilter->request_type == 2) {
                return $justifyRequests;
            } elseif ($requestFilter != null && $requestFilter->request_type == 3) {
                return $resignationRequests;
            } elseif ($requestFilter != null && $requestFilter->request_type == 4) {
                return $retrementRequests;
            } else {
                return $allRequests;
            }
        } else {
            return ['message' => "Unauthorized"];
        }
    }

    public function model()
    {
        return VacationRequest::class;
    }
}
