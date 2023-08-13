<?php

namespace App\Statuses;

class EmployeeStatus
{
    public const ON_DUTY = 1;
    public const ON_VACATION = 2;

    public static array $statuses = [self::ON_DUTY, self::ON_VACATION];
}
