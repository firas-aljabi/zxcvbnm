<?php

namespace App\Repository\Deposit;


use App\Models\Deposit;

use App\Repository\BaseRepositoryImplementation;
use App\Statuses\DepositStatus;
use App\Statuses\DepositType;
use App\Statuses\UserTypes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class DepositRepository extends BaseRepositoryImplementation
{
    public function getFilterItems($filter)
    {
        $records = Deposit::query();
        $records->when(isset($filter->orderBy), function ($records) use ($filter) {
            $records->orderBy($filter->getOrderBy(), $filter->getOrder());
        });
        return $records->paginate($filter->per_page);
    }

    public function create_deposit($data)
    {
        DB::beginTransaction();
        try {

            if (auth()->user()->type == UserTypes::HR || auth()->user()->type == UserTypes::ADMIN) {

                $deposit = new Deposit();
                $deposit->status = DepositStatus::PENDING;
                $deposit->user_id = $data['user_id'];
                $deposit->company_id = auth()->user()->company_id;

                if ($data['type'] == DepositType::CAR) {

                    $deposit->type = DepositType::CAR;
                    $deposit->car_number = $data['car_number'];
                    $deposit->car_model = $data['car_model'];
                    $deposit->manufacturing_year = $data['manufacturing_year'];
                    $deposit->Mechanic_card_number = $data['Mechanic_card_number'];

                    if (Arr::has($data, 'car_image')) {
                        $file = Arr::get($data, 'car_image');
                        $extention = $file->getClientOriginalExtension();
                        $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                        $file->move(public_path('images'), $file_name);
                        $image_file_path = public_path('images/' . $file_name);
                        $image_data = file_get_contents($image_file_path);
                        $base64_image = base64_encode($image_data);
                        $deposit->car_image = $base64_image;
                    }
                } elseif ($data['type'] == DepositType::LAPTOP) {
                    $deposit->type = DepositType::LAPTOP;
                    $deposit->laptop_type = $data['laptop_type'];
                    $deposit->serial_laptop_number = $data['serial_laptop_number'];
                    $deposit->laptop_color = $data['laptop_color'];

                    if (Arr::has($data, 'laptop_image')) {
                        $file = Arr::get($data, 'laptop_image');
                        $extention = $file->getClientOriginalExtension();
                        $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                        $file->move(public_path('images'), $file_name);
                        $image_file_path = public_path('images/' . $file_name);
                        $image_data = file_get_contents($image_file_path);
                        $base64_image = base64_encode($image_data);
                        $deposit->laptop_image = $base64_image;
                    }
                } elseif ($data['type'] == DepositType::MOBILE) {
                    $deposit->type = DepositType::MOBILE;
                    $deposit->serial_mobile_number = $data['serial_mobile_number'];
                    $deposit->mobile_color = $data['mobile_color'];

                    if (Arr::has($data, 'mobile_image')) {
                        $file = Arr::get($data, 'mobile_image');
                        $extention = $file->getClientOriginalExtension();
                        $file_name = Str::uuid() . date('Y-m-d') . '.' . $extention;
                        $file->move(public_path('images'), $file_name);
                        $image_file_path = public_path('images/' . $file_name);
                        $image_data = file_get_contents($image_file_path);
                        $base64_image = base64_encode($image_data);
                        $deposit->mobile_image = $base64_image;
                    }
                }

                $deposit->save();

                DB::commit();
                if ($deposit === null) {
                    return ['success' => false, 'message' => "Deposit was not created"];
                }
                return ['success' => true, 'data' => $deposit->load('user')];
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
        return Deposit::class;
    }
}
