<?php

namespace App\Http\Resources\Employees;

use App\Http\Resources\Admin\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'Net_Salary' => $this->salary,
            'Rewards' => $this->rewards,
            'Adversaries' => $this->adversaries,
            'Adversaries' => $this->adversaries,
            'Housing allowance' => $this->housing_allowance,
            'Transportation Allowance' => $this->transportation_allowance,
            'date' => $this->date,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
