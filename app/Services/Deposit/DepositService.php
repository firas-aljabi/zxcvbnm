<?php


namespace App\Services\Deposit;

use App\Interfaces\Deposit\DepositServiceInterface;
use App\Repository\Deposit\DepositRepository;
use App\Statuses\DepositStatus;
use App\Statuses\UserTypes;

class DepositService implements DepositServiceInterface
{

    public function __construct(private DepositRepository $depositRepository)
    {
    }
    public function create_deposit($data)
    {
        return $this->depositRepository->create_deposit($data);
    }

    public function approve_deposit($id)
    {
        $deposit = $this->depositRepository->getById($id);
        if (auth()->user()->type == UserTypes::EMPLOYEE && auth()->user()->company_id == $deposit['company_id']) {

            $deposit->status = DepositStatus::UN_PAID;
            $deposit->update();

            return ['success' => true, 'data' => $deposit->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }

    public function reject_deposit($request)
    {
        $deposit = $this->depositRepository->getById($request['deposit_id']);
        if (auth()->user()->type == UserTypes::EMPLOYEE && auth()->user()->company_id == $deposit['company_id']) {

            $deposit->status = DepositStatus::REJECTED;
            $deposit->reason_reject = $request['reason_reject'];
            $deposit->update();

            return ['success' => true, 'data' => $deposit->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }


    public function approve_clearance_request($id)
    {
        $deposit = $this->depositRepository->getById($id);
        if (auth()->user()->type == UserTypes::ADMIN || auth()->user()->type == UserTypes::HR && auth()->user()->company_id == $deposit['company_id']) {

            $deposit->status = DepositStatus::PAID;
            $deposit->update();

            return ['success' => true, 'data' => $deposit->load('user')];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }
}