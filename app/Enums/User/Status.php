<?php

namespace App\Enums\User;

enum Status: int
{
    case APPROVED = 1;
    case PENDING = 2;
    case BLOCKED = 3;


    public static function getAllAvailableValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
