<?php

namespace App\Services\Requests;

use App\Filter\JustifyRequests\JustifyRequestsFilter;
use App\Repository\Requests\EmployeeRequestRepository;
use App\Statuses\EmployeeRequestStatus;
use App\Statuses\EmployeeRequestsType;
use App\Statuses\JustifyStatus;
use App\Statuses\UserTypes;

class EmployeeRequestService
{
    public function __construct(private EmployeeRequestRepository $employeeRequestRepository)
    {
    }

    public function employee_request($data)
    {
        return $this->employeeRequestRepository->employee_request($data);
    }

    public function approve_employee_request($id)
    {
        $employeeRequest = $this->employeeRequestRepository->getById($id);
        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR && auth()->user()->company_id == $employeeRequest->company_id) {

            $employeeRequest->status = EmployeeRequestStatus::APPROVEED;
            $employeeRequest->update();

            return ['success' => true, 'data' => $employeeRequest->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }



    public function reject_employee_request($data)
    {
        return $this->employeeRequestRepository->reject_employee_request($data);
    }
}
