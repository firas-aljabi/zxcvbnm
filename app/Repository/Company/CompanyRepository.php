<?php

namespace App\Repository\Company;


use App\Models\Company;
use App\Models\Location;
use App\Repository\BaseRepositoryImplementation;
use App\Statuses\UserTypes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CompanyRepository extends BaseRepositoryImplementation
{
    public function getFilterItems($filter)
    {
        $records = Company::query();
        $records->when(isset($filter->orderBy), function ($records) use ($filter) {
            $records->orderBy($filter->getOrderBy(), $filter->getOrder());
        });
        return $records->paginate($filter->per_page);
    }

    public function create_company($data)
    {
        DB::beginTransaction();
        try {

            if (auth()->user()->type == UserTypes::SUPER_ADMIN) {

                $company = new Company();
                if (Arr::has($data, 'commercial_record')) {
                    $file = Arr::get($data, 'commercial_record');
                    $extention = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $company->commercial_record = $base64_image;
                }
                $company->name = $data['name'];
                $company->email = $data['email'];
                $company->start_commercial_record = $data['start_commercial_record'];
                $company->end_commercial_record = $data['end_commercial_record'];
                $company->save();

                Location::create([
                    'company_id' => $company->id,
                    'longitude' => $data['longitude'],
                    'latitude' => $data['latitude'],
                    'radius' => $data['radius'],
                ]);

                DB::commit();
                if ($company === null) {
                    return ['success' => false, 'message' => "Company was not created"];
                }
                return ['success' => true, 'data' => $company->load(['admin', 'locations'])];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update_company($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $data['company_id']) {
                $company = $this->updateById($data['company_id'], $data);
                if (Arr::has($data, 'commercial_record')) {
                    $file = Arr::get($data, 'commercial_record');
                    $extension = $file->getClientOriginalExtension();
                    $file_name = Str::uuid() . date('Y-m-d') . '.' . $extension;
                    $file->move(public_path('images'), $file_name);
                    $image_file_path = public_path('images/' . $file_name);
                    $image_data = file_get_contents($image_file_path);
                    $base64_image = base64_encode($image_data);
                    $company->commercial_record = $base64_image;
                    $company->save();
                }
                DB::commit();
                if ($company === null) {
                    return ['success' => false, 'message' => "Company was not Updated"];
                }
                return ['success' => true, 'data' => $company->load('admin')];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // public function update_company_location($data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         if (auth()->user()->type == UserTypes::SUPER_ADMIN) {
    //             $company = $this->updateById($data['company_id'], $data);

    //             if (Arr::has($data, 'commercial_record')) {
    //                 $file = Arr::get($data, 'commercial_record');
    //                 $extension = $file->getClientOriginalExtension();
    //                 $file_name = Str::uuid() . date('Y-m-d') . '.' . $extension;
    //                 $file->move(public_path('images'), $file_name);
    //                 $image_file_path = public_path('images/' . $file_name);
    //                 $image_data = file_get_contents($image_file_path);
    //                 $base64_image = base64_encode($image_data);
    //                 $company->commercial_record = $base64_image;
    //                 $company->save();
    //             }
    //             DB::commit();
    //             if ($company === null) {
    //                 return ['success' => false, 'message' => "Company was not Updated"];
    //             }
    //             return ['success' => true, 'data' => $company->load('admin')];
    //         } else {
    //             return ['success' => false, 'message' => "Unauthorized"];
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error($e->getMessage());
    //         return ['success' => false, 'message' => $e->getMessage()];
    //     }
    // }


    public function model()
    {
        return Company::class;
    }
}
