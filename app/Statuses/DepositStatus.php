<?php

namespace App\Statuses;

class  DepositStatus
{
    public const PAID = 1;
    public const UN_PAID = 2;
    public const PENDING = 3;
    public const REJECTED = 4;

    public static array $statuses = [self::PAID, self::UN_PAID, self::PENDING, self::REJECTED];
}
