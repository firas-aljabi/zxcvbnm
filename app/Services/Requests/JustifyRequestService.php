<?php

namespace App\Services\Requests;

use App\Filter\JustifyRequests\JustifyRequestsFilter;
use App\Repository\Requests\JustifyRequestRepository;
use App\Statuses\JustifyStatus;
use App\Statuses\UserTypes;

class JustifyRequestService
{
    public function __construct(private JustifyRequestRepository $justifyRequestRepository)
    {
    }

    public function add_justify_request($data)
    {

        return $this->justifyRequestRepository->add_justify_request($data);
    }

    public function show($id)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {

            return ['success' => true, 'data' => $this->justifyRequestRepository->with('user')->getById($id)];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }


    public function getMyJustifyRequests(JustifyRequestsFilter $justifyRequestRepository)
    {
        if ($justifyRequestRepository != null) {
            return $this->justifyRequestRepository->getFilterItems($justifyRequestRepository);
        } else {
            return $this->justifyRequestRepository->paginate();
        }
    }


    public function getJustifyRequests(JustifyRequestsFilter $justifyRequestRepository = null)
    {
        if ($justifyRequestRepository != null) {
            return $this->justifyRequestRepository->getFilterItemsForAdmin($justifyRequestRepository);
        } else {
            $data = $this->justifyRequestRepository->paginate();
            return ['success' => true, 'data' => $data];
        }
    }


    public function approve_justify_request($id)
    {
        if (auth()->user()->type == UserTypes::ADMIN) {
            $justifyAfterAccept = $this->justifyRequestRepository->getById($id);
            $justifyAfterAccept->status = JustifyStatus::APPROVED;
            $justifyAfterAccept->update();

            return ['success' => true, 'data' => $justifyAfterAccept->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function reject_justify_request($data)
    {
        return $this->justifyRequestRepository->reject_justify_request($data);
    }
}
