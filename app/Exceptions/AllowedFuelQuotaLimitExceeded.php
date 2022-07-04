<?php

namespace App\Exceptions;

class AllowedFuelQuotaLimitExceeded extends \DomainException
{
    public function __construct(string $message = "Allowed quota limit exceeded", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
