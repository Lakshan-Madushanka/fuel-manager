<?php

namespace App\Enums\User;

enum Role: int
{
    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case REGULAR = 3;

    public static function getAllAvailableValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
