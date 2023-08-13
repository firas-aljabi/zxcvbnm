<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\GetMonthlyShiftListRequest;
use App\Http\Requests\Requests\CreateVacationRequest;
use App\Http\Requests\Requests\GetMonthlyShiftRequest;
use App\Http\Requests\Requests\GetRequestListRequest;
use App\Http\Requests\Requests\GetVacationRequestListRequest;
use App\Http\Requests\Requests\RejectVacationRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\Requests\MonthlyShiftResource;
use App\Http\Resources\Requests\VacationResource;
use App\Services\Requests\VacationRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacationRquestController extends Controller
{

    public function __construct(private VacationRequestService $vacationRequestService)
    {
    }


    public function store(CreateVacationRequest $request)
    {
        $createdData =  $this->vacationRequestService->add_vacation_request($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = VacationResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function approve_vacation_request($id)
    {
        $vacationRequest = $this->vacationRequestService->approve_vacation_request($id);
        if ($vacationRequest['success']) {
            $newData = $vacationRequest['data'];
            $returnData = VacationResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $vacationRequest['message']];
        }
    }
    public function reject_vacation_request(RejectVacationRequest $request)
    {
        $createdData =  $this->vacationRequestService->reject_vacation_request($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = VacationResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function show($id)
    {
        $vacationRequest = $this->vacationRequestService->show($id);
        if ($vacationRequest['success']) {
            $alert = $vacationRequest['data'];
            $returnData = VacationResource::make($alert);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $vacationRequest['message']];
        }
    }


    public function getMyVacationRequests(GetVacationRequestListRequest $request)
    {
        $data = $this->vacationRequestService->getMyVacationRequests($request->generateFilter());
        $returnData = VacationResource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }

    public function getVacationRequests(GetVacationRequestListRequest $request)
    {
        $data = $this->vacationRequestService->getVacationRequests($request->generateFilter());
        if ($data['success']) {
            $newData = $data['data'];
            $returnData = VacationResource::collection($newData);
            $pagination = PaginationResource::make($data['data']);
            return ApiResponseHelper::sendResponseWithPagination(
                new Result($returnData, $pagination, "DONE")
            );
        } else {
            return ['message' => $data['message']];
        }
    }


    public function getMyMonthlyShift(GetMonthlyShiftListRequest $request)
    {
        $data = $this->vacationRequestService->getMonthlyShiftListRequest($request->generateFilter());
        if ($data['success']) {
            $newData = $data['data'];
            $returnData = MonthlyShiftResource::collection($newData);
            $pagination = PaginationResource::make($data['data']);
            return ApiResponseHelper::sendResponseWithPagination(
                new Result($returnData, $pagination, "DONE")
            );
        } else {
            return ['message' => $data['message']];
        }
    }


    public function getMonthlyData(GetMonthlyShiftListRequest $request)
    {
        $data = $this->vacationRequestService->getMonthlyData($request->generateFilter());
        return $data;
    }

    public function get_all_requests(GetRequestListRequest $request)
    {
        $data = $this->vacationRequestService->getRequests($request->generateFilter());
        return $data;
    }
}
