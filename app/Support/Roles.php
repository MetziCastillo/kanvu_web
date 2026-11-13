<?php

namespace App\Support;

class Roles
{
    public const ADMIN             = 1;
    public const MEDICAL_DEPARTMENT = 2;
    public const PSYCHOLOGY_DEPARTMENT   = 3;

    public const NURSE     = 4;
    public const PSYCHOLOGIST     = 5;


    public const BOSS_VISIBLE_ROLES = [
        self::MEDICAL_DEPARTMENT    => [self::NURSE],
        self::PSYCHOLOGY_DEPARTMENT => [self::PSYCHOLOGIST],
    ];

    public static function visibleRolesFor(int $roleId): ?array
    {
        return self::BOSS_VISIBLE_ROLES[$roleId] ?? null;
    }

    public static function isBoss(int $roleId): bool
    {
        return array_key_exists($roleId, self::BOSS_VISIBLE_ROLES);
    }
}
