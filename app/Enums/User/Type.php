<?php

namespace App\Enums\User;

enum Type: int
{
    case SPECIAL = 1;
    case REGULAR = 2;

    public static function getAllAvailableValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
