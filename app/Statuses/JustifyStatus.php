<?php

namespace App\Statuses;

class JustifyStatus
{
    public const APPROVED = 1;
    public const REJECTED = 2;
    public const PENDING = 3;

    public static array $statuses = [self::APPROVED, self::REJECTED, self::PENDING];
}
