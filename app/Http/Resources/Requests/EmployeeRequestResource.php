<?php

namespace App\Http\Resources\Requests;

use App\Statuses\EmployeeRequestStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeRequestResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->status == EmployeeRequestStatus::REJECTED) {
            return [
                'id' => $this->id,
                'type' => $this->type,
                'date' => $this->date,
                'reject_reason' => $this->reject_reason,
                'user' => $this->whenLoaded('user', function () {
                    return [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ];
                }),
                'attachments' => $this->attachments,
            ];
        } else {
            return [
                'id' => $this->id,
                'type' => $this->type,
                'date' => $this->date,
                'user' => $this->whenLoaded('user', function () {
                    return [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ];
                }),
                'attachments' => $this->attachments,
            ];
        }
    }
}
