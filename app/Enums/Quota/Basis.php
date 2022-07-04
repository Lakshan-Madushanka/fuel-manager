<?php

namespace App\Enums\Quota;

enum Basis: int
{
    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
}
