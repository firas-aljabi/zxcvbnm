<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\CreateEmployeeRequest;
use App\Http\Requests\Requests\RejectEmployeeRequest;
use App\Http\Resources\Requests\EmployeeRequestResource;
use App\Services\Requests\EmployeeRequestService;

class EmployeeRequestController extends Controller
{
    public function __construct(private EmployeeRequestService $employeeRequestService)
    {
    }
    public function employee_request(CreateEmployeeRequest $request)
    {
        $createdData =  $this->employeeRequestService->employee_request($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeRequestResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function approve_employee_request($id)
    {
        $employeeRequest = $this->employeeRequestService->approve_employee_request($id);
        if ($employeeRequest['success']) {
            $newData = $employeeRequest['data'];
            $returnData = EmployeeRequestResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $employeeRequest['message']];
        }
    }

    public function reject_employee_request(RejectEmployeeRequest $request)
    {
        $createdData =  $this->employeeRequestService->reject_employee_request($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeRequestResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
}
