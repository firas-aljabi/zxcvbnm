<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Holiday\CreateAnnualHolidayRequest;
use App\Http\Requests\Holiday\CreateWeeklyHolidayRequest;
use App\Http\Requests\Holiday\GetHolidaysRequest;
use App\Http\Requests\Holiday\UpdateAnnualHolidayRequest;
use App\Http\Requests\Holiday\UpdateWeeklyHolidayRequest;
use App\Http\Resources\Holiday\AnnualHolidayResource;
use App\Http\Resources\Holiday\HolidayRrsource;
use App\Http\Resources\Holiday\WeeklyHolidayResource;
use App\Http\Resources\PaginationResource;
use App\Services\Holiday\HolidayService;

class HolidayController extends Controller
{
    public function __construct(private HolidayService $holidayService)
    {
    }

    public function create_weekly_holiday(CreateWeeklyHolidayRequest $request)
    {
        $createdData =  $this->holidayService->create_weekly_holiday($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = WeeklyHolidayResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function create_annual_holiday(CreateAnnualHolidayRequest $request)
    {
        $createdData =  $this->holidayService->create_annual_holiday($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = AnnualHolidayResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function update_weekly_holiday(UpdateWeeklyHolidayRequest $request)
    {
        $createdData =  $this->holidayService->update_weekly_holiday($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = WeeklyHolidayResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function update_annual_holiday(UpdateAnnualHolidayRequest $request)
    {
        $createdData =  $this->holidayService->update_annual_holiday($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = AnnualHolidayResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function list_of_holidays(GetHolidaysRequest $request)
    {
        $data = $this->holidayService->list_of_holidays($request->generateFilter());
        $returnData = HolidayRrsource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }
}
