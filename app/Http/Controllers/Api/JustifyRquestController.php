<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\CreateJustifyRequest;
use App\Http\Requests\Requests\GetJustifyRequestListRequest;
use App\Http\Requests\Requests\RejectJusifyRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\Requests\JustifyResource;
use App\Services\Requests\JustifyRequestService;
use Illuminate\Http\Request;

class JustifyRquestController extends Controller
{
    public function __construct(private JustifyRequestService $justifyRequestService)
    {
    }


    public function store(CreateJustifyRequest $request)
    {

        $createdData =  $this->justifyRequestService->add_justify_request($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = JustifyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function show($id)
    {
        $justifyRequest = $this->justifyRequestService->show($id);

        if ($justifyRequest['success']) {

            $alert = $justifyRequest['data'];

            $returnData = JustifyResource::make($alert);


            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $justifyRequest['message']];
        }
    }

    public function getMyJustifyRequests(GetJustifyRequestListRequest $request)
    {
        $data = $this->justifyRequestService->getMyJustifyRequests($request->generateFilter());
        $returnData = JustifyResource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }


    public function getJustifyRequests(GetJustifyRequestListRequest $request)
    {
        $data = $this->justifyRequestService->getJustifyRequests($request->generateFilter());
        if ($data['success']) {
            $newData = $data['data'];
            $returnData = JustifyResource::collection($newData);
            $pagination = PaginationResource::make($data['data']);
            return ApiResponseHelper::sendResponseWithPagination(
                new Result($returnData, $pagination, "DONE")
            );
        } else {
            return ['message' => $data['message']];
        }
    }
    public function approve_justify_request($id)
    {
        $justifyRequest = $this->justifyRequestService->approve_justify_request($id);
        if ($justifyRequest['success']) {
            $newData = $justifyRequest['data'];
            $returnData = JustifyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $justifyRequest['message']];
        }
    }


    public function reject_justify_request(RejectJusifyRequest $request)
    {
        $createdData =  $this->justifyRequestService->reject_justify_request($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = JustifyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
}
