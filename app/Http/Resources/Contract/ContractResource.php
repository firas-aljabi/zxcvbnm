<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Start Employee Contract Date' => $this->start_contract_date,
            'End Employee Contract Date' => $this->end_contract_date,
            'Contract Termination Date' => $this->contract_termination_date,
            'Contract Termination Period' => $this->contract_termination_period,
            'Contract Termination Reason' => $this->contract_termination_reason,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
