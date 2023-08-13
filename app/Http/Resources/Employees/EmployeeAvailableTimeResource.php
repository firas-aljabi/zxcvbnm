<?php

namespace App\Http\Resources\Employees;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAvailableTimeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'hours_daily' => $this->hours_daily,
            'days_annual' => $this->days_annual,
        ];
    }
}
