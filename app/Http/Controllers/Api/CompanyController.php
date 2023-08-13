<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateComapnyRequest;
use App\Http\Requests\Company\UpdateCompanyLocationRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\Company\ComapnyResource;
use App\Services\Company\CompanyService;

class CompanyController extends Controller
{

    public function __construct(private CompanyService $companyService)
    {
    }


    public function store(CreateComapnyRequest $request)
    {
        $createdData =  $this->companyService->create_company($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = ComapnyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function show($id)
    {
        $company = $this->companyService->show($id);

        if ($company['success']) {
            $alert = $company['data'];
            $returnData = ComapnyResource::make($alert);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $company['message']];
        }
    }



    public function update_comapny(UpdateCompanyRequest $request)
    {
        $createdData =  $this->companyService->update_company($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = ComapnyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function update_company_location(UpdateCompanyLocationRequest $request)
    {
        $createdData =  $this->companyService->update_company_location($request->validated());

        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = ComapnyResource::make($newData);
            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }




    public function update_location_company()
    {
    }
}
