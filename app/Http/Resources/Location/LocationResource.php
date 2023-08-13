<?php

namespace App\Http\Resources\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Longitude' => $this->longitude,
            'Latitude' => $this->latitude,
            'Radius' => $this->radius,
        ];
    }
}