<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\CreateDepositRequest;
use App\Http\Requests\Deposit\RejectDepositRequest;
use App\Http\Resources\Deposit\DepositResource;
use App\Services\Deposit\DepositService;

class DepositController extends Controller
{

    public function __construct(private DepositService $depositService)
    {
    }
    public function store(CreateDepositRequest $request)
    {
        $createdData =  $this->depositService->create_deposit($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = DepositResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function approve_deposit($id)
    {
        $createdData = $this->depositService->approve_deposit($id);
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = DepositResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function reject_deposit(RejectDepositRequest $request)
    {
        $createdData = $this->depositService->reject_deposit($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = DepositResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function approve_clearance_request($id)
    {
        $createdData = $this->depositService->approve_clearance_request($id);
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = DepositResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
}
