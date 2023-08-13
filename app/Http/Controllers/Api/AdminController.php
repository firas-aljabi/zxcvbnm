<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\ErrorResult;
use App\ApiHelper\Result;
use App\ApiHelper\SuccessResult;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\TerminateContractRequest;
use App\Http\Requests\Employees\CheckInAttendanceRequest;
use App\Http\Requests\Employees\CheckOutAttendanceRequest;
use App\Http\Requests\Employees\CreateAdminRequest;
use App\Http\Requests\Employees\CreateEmployeeRequest;
use App\Http\Requests\Employees\DetermineWorkingHoursRequest;
use App\Http\Requests\Employees\GetEmployeesAttendancesListRequest;
use App\Http\Requests\Employees\GetEmployeesListRequest;
use App\Http\Requests\Employees\GetEmployeesSalariesListRequest;
use App\Http\Requests\Employees\RewerdsAdversriesSalaryRequest;
use App\Http\Requests\Employees\UpdateEmployeeContractRequest;
use App\Http\Requests\Employees\UpdateSalaryRequest;
use App\Http\Requests\Nationalitie\GetNationalitiesRequest;
use App\Http\Resources\Admin\DashboardDataResource;
use App\Http\Resources\Admin\EmployeeResource;
use App\Http\Resources\Contract\ContractResource;
use App\Http\Resources\Employees\AttendanceResource;
use App\Http\Resources\Employees\EmployeeAvailableTimeResource;
use App\Http\Resources\Employees\SalaryResource;
use App\Http\Resources\Nationalitie\NationalitiesRrsource;
use App\Http\Resources\PaginationResource;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct(private AdminService $adminService)
    {
    }

    public function store(CreateEmployeeRequest $request)
    {
        $createdData =  $this->adminService->create_employee($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }





    public function store_hr(CreateEmployeeRequest $request)
    {
        $createdData =  $this->adminService->create_hr($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function store_admin(CreateAdminRequest $request)
    {
        $createdData =  $this->adminService->create_admin($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function determine_working_hours(DetermineWorkingHoursRequest $request)
    {
        $createdData =  $this->adminService->determine_working_hours($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeAvailableTimeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function update_salary(UpdateSalaryRequest $request)
    {
        $createdData =  $this->adminService->update_salary($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function update_employment_contract(UpdateEmployeeContractRequest $request)
    {
        $createdData =  $this->adminService->update_employment_contract($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = EmployeeResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }

    public function termination_employees_contract(TerminateContractRequest $request)
    {
        $createdData =  $this->adminService->termination_employees_contract($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = ContractResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }


    public function check_in_attendance(CheckInAttendanceRequest $request)
    {
        $createdData =  $this->adminService->check_in_attendance($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = AttendanceResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function check_out_attendance(CheckOutAttendanceRequest $request)
    {
        $createdData =  $this->adminService->check_out_attendance($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = AttendanceResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function reward_adversaries_salary(RewerdsAdversriesSalaryRequest $request)
    {
        $createdData =  $this->adminService->reward_adversaries_salary($request->validated());
        if ($createdData['success']) {
            $newData = $createdData['data'];
            $returnData = SalaryResource::make($newData);

            return ApiResponseHelper::sendResponse(
                new Result($returnData, "Done")
            );
        } else {
            return ['message' => $createdData['message']];
        }
    }
    public function destroyEmployee($id)
    {
        $deletionResult = $this->adminService->deleteEmployee($id);

        if (is_string($deletionResult)) {
            return ApiResponseHelper::sendErrorResponse(
                new ErrorResult($deletionResult)
            );
        }

        return ApiResponseHelper::sendSuccessResponse(
            new SuccessResult("Done", $deletionResult)
        );
    }
    public function getDashboardData()
    {
        $data = $this->adminService->getDashboardData();
        $returnData = DashboardDataResource::make($data);
        return ApiResponseHelper::sendResponse(
            new Result($returnData, "DONE")
        );
    }
    public function getEmployeesList(GetEmployeesListRequest $request)
    {
        $data = $this->adminService->getEmployees($request->generateFilter());
        $returnData = EmployeeResource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }
    public function employees_salaries(GetEmployeesSalariesListRequest $request)
    {
        $data = $this->adminService->employees_salaries($request->generateFilter());

        if ($data['success']) {
            $newData = $data['data'];
            $returnData = SalaryResource::collection($newData);
            $pagination = PaginationResource::make($data['data']);
            return ApiResponseHelper::sendResponseWithPagination(
                new Result($returnData, $pagination, "DONE")
            );
        } else {
            return ['message' => $data['message']];
        }
    }

    public function employees_attendances(GetEmployeesAttendancesListRequest $request)
    {
        $data = $this->adminService->employees_attendances($request->generateFilter());

        if ($data['success']) {
            $newData = $data['data'];
            $returnData = AttendanceResource::collection($newData);
            $pagination = PaginationResource::make($data['data']);
            return ApiResponseHelper::sendResponseWithPagination(
                new Result($returnData, $pagination, "DONE")
            );
        } else {
            return ['message' => $data['message']];
        }
    }

    public function getEmployee($id)
    {
        $employeeData = $this->adminService->showEmployee($id);
        $returnData = EmployeeResource::make($employeeData);
        return ApiResponseHelper::sendResponse(
            new Result($returnData,  "DONE")
        );
    }
    public function profile()
    {
        $employeeData = $this->adminService->profile();
        $returnData = EmployeeResource::make($employeeData);
        return ApiResponseHelper::sendResponse(
            new Result($returnData,  "DONE")
        );
    }

    public function list_of_nationalities(GetNationalitiesRequest $request)
    {
        $data = $this->adminService->list_of_nationalities($request->generateFilter());
        $returnData = NationalitiesRrsource::collection($data);
        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }
}
