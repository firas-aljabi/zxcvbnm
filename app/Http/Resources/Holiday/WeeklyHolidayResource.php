<?php

namespace App\Http\Resources\Holiday;

use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyHolidayResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'date' => $this->date,
        ];
    }
}
