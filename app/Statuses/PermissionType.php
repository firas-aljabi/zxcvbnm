<?php

namespace App\Statuses;

class PermissionType
{
    public const TRUE = 0;
    public const FALSE = 1;


    public static array $statuses = [self::TRUE, self::FALSE];
}
