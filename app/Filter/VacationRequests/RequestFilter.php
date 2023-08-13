<?php

namespace App\Filter\VacationRequests;

use App\Filter\OthersBaseFilter;

class RequestFilter extends OthersBaseFilter
{
    public ?int $request_type = null;


    public function getRequestType(): int
    {
        return $this->request_type;
    }


    public function setRequestType(int $request_type): void
    {
        $this->request_type = $request_type;
    }
}
