<?php

namespace App\Services\Requests;

use App\Filter\VacationRequests\MonthlyShiftFilter;
use App\Filter\VacationRequests\RequestFilter;
use App\Filter\VacationRequests\VacationRequestFilter;
use App\Interfaces\Requests\VacationServiceInterface;
use App\Query\Employee\GetMonthlyShiftQuery;
use App\Repository\Requests\VacationRequestRepository;
use App\Statuses\UserTypes;
use App\Statuses\VacationRequestStatus;
use App\Statuses\VacationRequestTypes;
use Illuminate\Support\Facades\DB;

class VacationRequestService implements VacationServiceInterface
{
    public function __construct(private VacationRequestRepository $vacationRequestRepository, private GetMonthlyShiftQuery $getMonthlyShiftQuery)
    {
    }

    public function add_vacation_request($data)
    {
        return $this->vacationRequestRepository->add_vacation_request($data);
    }

    public function approve_vacation_request($id)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {
            $vacationAfterAccept = $this->vacationRequestRepository->getById($id);
            $vacationAfterAccept->status = VacationRequestStatus::APPROVED;
            $vacationAfterAccept->update();

            return ['success' => true, 'data' => $vacationAfterAccept->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }
    public function reject_vacation_request($data)
    {
        return $this->vacationRequestRepository->reject_vacation_request($data);
    }

    public function show($id)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {
            return $this->vacationRequestRepository->with('user')->getById($id);
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function getMyVacationRequests(VacationRequestFilter $vacationRequestFilter = null)
    {
        if ($vacationRequestFilter != null) {
            return $this->vacationRequestRepository->getFilterItems($vacationRequestFilter);
        } else {
            return $this->vacationRequestRepository->paginate();
        }
    }


    public function getVacationRequests(VacationRequestFilter $vacationRequestFilter = null)
    {
        if ($vacationRequestFilter != null) {
            return $this->vacationRequestRepository->getFilterItemsForAdmin($vacationRequestFilter);
        } else {
            $data = $this->vacationRequestRepository->paginate();
            return ['success' => true, 'data' => $data];
        }
    }

    public function getMonthlyShiftListRequest(MonthlyShiftFilter $monthlyShiftFilter = null)
    {
        if ($monthlyShiftFilter != null) {
            return $this->vacationRequestRepository->getMonthlyShiftList($monthlyShiftFilter);
        } else {
            $data = $this->vacationRequestRepository->paginate();
            return ['success' => true, 'data' => $data];
        }
    }


    public function getRequests($filter)
    {
        return $this->vacationRequestRepository->getRequests($filter);
    }

    public function getMonthlyData($filter)
    {
        return $this->getMonthlyShiftQuery->getMonthlyData($filter);
    }
}
