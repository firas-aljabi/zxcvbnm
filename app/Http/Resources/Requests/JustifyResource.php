<?php

namespace App\Http\Resources\Requests;

use App\Http\Resources\Admin\EmployeeResource;
use App\Statuses\JustifyStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class JustifyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if ($this->status == JustifyStatus::REJECTED) {
            return [
                "id" => $this->id,
                "type" => $this->type,
                "status" => $this->status,
                "reason" => $this->reason,
                "reject_reason" => $this->reject_reason,
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
                "medical_report_file" => $this->medical_report_file,
                'user' => EmployeeResource::make($this->whenLoaded('user')),
            ];
        } else {
            return [
                "id" => $this->id,
                "type" => $this->type,
                "status" => $this->status,
                "reason" => $this->reason,
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
                "medical_report_file" => $this->medical_report_file,
                'user' => EmployeeResource::make($this->whenLoaded('user')),
            ];
        }
    }
}
