<?php


namespace App\Services\Company;

use App\Interfaces\Comapny\CompanyServiceInterface;
use App\Repository\Company\CompanyRepository;
use App\Statuses\UserTypes;

class CompanyService implements CompanyServiceInterface
{

    public function __construct(private CompanyRepository $companyRepository)
    {
    }
    public function create_company($data)
    {
        return $this->companyRepository->create_company($data);
    }


    public function update_company($data)
    {
        return $this->companyRepository->update_company($data);
    }


    public function update_company_location($data)
    {
        return $this->companyRepository->update_company_location($data);
    }






    public function show($id)
    {
        if (auth()->user()->type == UserTypes::ADMIN && auth()->user()->company_id == $id || auth()->user()->type == UserTypes::SUPER_ADMIN) {

            return ['success' => true, 'data' => $this->companyRepository->with('admin')->getById($id)];
        } else {
            return ['success' => false, 'message' => "Unauthorized"];
        }
    }
}
