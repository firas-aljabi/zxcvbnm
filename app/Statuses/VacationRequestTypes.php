<?php

namespace App\Statuses;

class VacationRequestTypes
{
    public const HOURLY = 1;
    public const DAILY = 2;
    public const ANNUL = 3;
    public const DEATH = 4;
    public const SATISFYING  = 5;
    public const PILGRIMAME = 6;
    public const NEW_BABY = 7;
    public const EXAM = 8;
    public const PREGNANT_WOMAN = 9;
    public const METERNITY = 10;
    public const SICK_CHILD = 11;

    public static array $statuses = [self::HOURLY, self::DAILY, self::ANNUL,   self::DEATH, self::SATISFYING, self::PILGRIMAME, self::NEW_BABY, self::EXAM, self::PREGNANT_WOMAN,   self::METERNITY, self::SICK_CHILD];
}
