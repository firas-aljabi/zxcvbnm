<?php

namespace App\Http\Resources\Deposit;

use App\Statuses\DepositType;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{

    public function toArray($request)
    {
        if ($this->type == DepositType::CAR) {
            return [
                "id" => $this->id,
                "type" => $this->type,
                "Status" => $this->status == 2 ? 'Un Paid' : "Paid",
                "Car Number" => $this->car_number,
                "Car Model" => $this->car_model,
                "Manufacturing Year" => $this->manufacturing_year,
                "Mechanic Card Number" => $this->Mechanic_card_number,
                "Car Image" => $this->car_image,
            ];
        } elseif ($this->type == DepositType::LAPTOP) {
            return [
                "id" => $this->id,
                "type" => $this->type,
                "Status" => $this->status == 2 ? 'Un Paid' : "Paid",
                "Laptop Type" => $this->laptop_type,
                "Serial Laptop Number" => $this->serial_laptop_number,
                "Laptop Color" => $this->laptop_color,
                "Laptop Image" => $this->laptop_image,
            ];
        } else {
            return [
                "id" => $this->id,
                "type" => $this->type,
                "Status" => $this->status == 2 ? 'Un Paid' : "Paid",
                "Serial Mobile Mumber" => $this->serial_mobile_number,
                "Mobile Color" => $this->mobile_color,
                "Mobile Image" => $this->mobile_image,
            ];
        }
    }
}
