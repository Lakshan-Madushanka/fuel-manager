<?php

namespace App\Services;

use App\Enums\User\Type;
use App\Models\User;

class UserFuelConsumptionService
{
    private readonly float $allowedQuota;
    private readonly FuelQuotaService $fqs;

    public function __construct(private readonly User $user)
    {
        $this->fqs = app(FuelQuotaService::class);
        $this->setAllowedQuota();
    }

    public function isFuelQuotaLimitExceeded(float $amount): bool
    {
        return $this->user->lastWeekConsumption + $amount >= $this->allowedQuota;
    }

    public function getRemainingFuelQuota(): float
    {
        return $this->allowedQuota - $this->user->currentPlanFuelConsumptionAmount;
    }

    public function setAllowedQuota(): void
    {
        $allowedQuota = $this->user->type->value === Type::REGULAR->value
            ? $this->fqs->regularLimit : $this->fqs->specialLimit;

        $this->allowedQuota = $allowedQuota;
    }
}
