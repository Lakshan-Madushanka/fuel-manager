<?php

namespace App\Enums\User;

enum Role: int
{
    case OWNER = 1;
    case SUPER_ADMIN = 2;
    case ADMIN = 3;
    case REGULAR = 4;

    public static function getAllAvailableValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
