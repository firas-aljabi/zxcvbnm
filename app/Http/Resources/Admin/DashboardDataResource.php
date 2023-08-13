<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;


class DashboardDataResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'all_employees_count' => number_format($this->all_employees_count),
            'attendance_rate' => number_format($this->attendance_rate),
            'on_duty_employees_count' => number_format($this->on_duty_employees_count),
            'on_vacation_employees_count' => number_format($this->on_vacation_employees_count),
            'on_duty_employees_percentage' => number_format($this->on_duty_employees_percentage),
            'on_vacation_employees_percentage' => number_format($this->on_vacation_employees_percentage),
            'male_employees_percentage' => $this->male_employees,
            'female_employees_percentage' => $this->female_employees,
            'nationalities_rate' => $this->nationalities_rate,
            'contract_expiration_percentage' => number_format($this->contract_expiration_percentage),
            'contract_expiration' => $this->contract_expiration,
            'expired_passports_percentage' => $this->expired_passports_percentage,
            'expired_passports' => $this->expired_passports,

        ];
    }
}
