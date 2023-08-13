<?php

namespace App\Repository\Holiday;


use App\Models\Holiday;

use App\Repository\BaseRepositoryImplementation;
use App\Statuses\HolidayTypes;
use App\Statuses\UserTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HolidayRepository extends BaseRepositoryImplementation
{
    public function getFilterItems($filter)
    {
        $records = Holiday::query()->where('company_id', auth()->user()->company_id);
        $records->when(isset($filter->orderBy), function ($records) use ($filter) {
            $records->orderBy($filter->getOrderBy(), $filter->getOrder());
        });
        return $records->paginate($filter->per_page);
    }

    public function create_weekly_holiday($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::ADMIN) {

                $holiday = new Holiday();
                $holiday->day = $data['day'];
                $holiday->date = $data['date'];
                $holiday->type = HolidayTypes::WEEKLY;
                $holiday->company_id = auth()->user()->company_id;
                $holiday->save();

                DB::commit();
                if ($holiday === null) {
                    return ['success' => false, 'message' => "Holiday was not created"];
                }
                return ['success' => true, 'data' => $holiday];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function create_annual_holiday($data)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->type == UserTypes::ADMIN) {

                $holiday = new Holiday();
                $holiday->holiday_name = $data['holiday_name'];
                $holiday->start_date = $data['start_date'];
                $holiday->end_date = $data['end_date'];
                $holiday->type = HolidayTypes::ANNUL;
                $holiday->company_id = auth()->user()->company_id;
                $holiday->save();
                DB::commit();
                if ($holiday === null) {
                    return ['success' => false, 'message' => "Holiday was not created"];
                }
                return ['success' => true, 'data' => $holiday];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function update_weekly_holiday($data)
    {
        DB::beginTransaction();

        $holiday = $this->getById($data['holiday_id']);

        try {
            if (auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $holiday->company_id) {

                $newHoliday = $this->updateById($data['holiday_id'], $data);

                DB::commit();
                if ($newHoliday === null) {
                    return ['success' => false, 'message' => "Holiday was not Updated"];
                }
                return ['success' => true, 'data' => $newHoliday];
            } else {
                return ['success' => false, 'message' => "Unauthorized"];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function list_of_holidays($filter)
    {
        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR) {
            $records = Holiday::query()->where('company_id', auth()->user()->company_id);
            return $records->paginate(100);
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function update_annual_holiday($data)
    {
        DB::beginTransaction();

        $holiday = $this->getById($data['holiday_id']);

        try {
            if (auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $holiday->company_id) {

                $newHoliday = $this->updateById($data['holiday_id'], $data);

                DB::commit();
                if ($newHoliday === null) {
                    return ['success' => false, 'message' => "Holiday was not Updated"];
                }
                return ['success' => true, 'data' => $newHoliday];
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
        return Holiday::class;
    }
}
