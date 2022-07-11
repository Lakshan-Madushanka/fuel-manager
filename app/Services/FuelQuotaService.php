<?php

namespace App\Services;

use App\Enums\Quota\Basis;
use App\Helpers\DateHelpers;
use App\Models\Quota;
use JetBrains\PhpStorm\ArrayShape;

class FuelQuotaService
{
    public readonly float $regularLimit;
    public readonly float $specialLimit;
    public readonly Basis $basis;

    public function __construct()
    {
        $this->initProperties();
    }

    private function initProperties(): void
    {
        $quotaDetails = self::getCurrentQuotaPlan();

        $this->regularLimit = $quotaDetails->regular_amount;
        $this->specialLimit = $quotaDetails->special_amount;
        $this->basis = $quotaDetails->basis;
    }

    #[ArrayShape([
        'startDate' => "\Illuminate\Support\Carbon|string", 'endDate' => "\Illuminate\Support\Carbon|string",
    ])]
    public function getCurrentPlanDates(): array
    {
        $plan = ['startDate' => '', 'endDate' => ''];

        switch ($this->basis->value) {
            case Basis::WEEKLY->value:
                $plan['startDate'] = DateHelpers::getWeekStartTime();
                $plan['endDate'] = DateHelpers::getWeekEndTime();

                return $plan;
            case Basis::DAILY->value:
                $plan['startDate'] = DateHelpers::getDayStartTime();
                $plan['endDate'] = DateHelpers::getDayEndTime();

                return $plan;
            case Basis::MONTHLY->value:
                $plan['startDate'] = DateHelpers::getMonthStartTime();
                $plan['endDate'] = DateHelpers::getMonthEndTime();

                return $plan;

        }
    }

    public static function getCurrentQuotaPlan()
    {
        return Quota::query()
            ->where('is_current_plan', true)
            ->first();
    }

    public static function makeAllPlansDeactivate(): void
    {
        Quota::query()
            ->where('is_current_plan', true)
            ->chunkById(200, function ($plans) {
                $plans->each->update(['is_current_plan' => false]);
            }, 'id');
    }
}
