<?php

namespace App\Http\Resources\Employees;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Date' => $this->date,
            'Login Time' => $this->login_time,
            'Logout Time' => $this->logout_time,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
