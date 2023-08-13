<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alerts\CrateAlertRequest;
use App\Http\Requests\Alerts\GetAlertsListRequest;
use App\Http\Resources\Alerts\AlertResource;
use App\Http\Resources\PaginationResource;
use App\Services\Alerts\AlertService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function __construct(private AlertService $alertService)
    {
    }

    public function store(CrateAlertRequest $request)
    {
        $createdData =  $this->alertService->create_alert($request->validated());
        if ($createdData['success']) {
            $alert = $createdData['data'];
            $returnData = AlertResource::make($alert);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function getMyAlert(GetAlertsListRequest $request)
    {
        $data = $this->alertService->getMyAlerts($request->generateFilter());

        $returnData = AlertResource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }
}
