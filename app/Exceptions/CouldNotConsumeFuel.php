<?php

namespace App\Exceptions;

class CouldNotConsumeFuel extends \DomainException
{
    public static function AllowedQuotaLimitExceeded(float $amount)
    {
        return new static("Allowed quota limit exceeded (only $amount available)");
    }
}
