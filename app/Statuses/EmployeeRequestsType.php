<?php

namespace App\Statuses;

class  EmployeeRequestsType
{
    public const  RETIREMENT  = 1;
    public const  RESIGNATION = 2;

    public static array $statuses = [self::RETIREMENT, self::RESIGNATION];
}
