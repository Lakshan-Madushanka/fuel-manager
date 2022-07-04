<?php

namespace App\Actions\Dashboard;

use App\Helpers\DateHelpers;
use App\Models\Consumption;
use Illuminate\Support\Facades\DB;

class GetCurrentFuelConsumptionReport
{
    public function execute(): array
    {
        $currentDayConsumptionQuery = $this->getCurrentConsumpptionQuery(
            DateHelpers::getDayStartTime(),
            DateHelpers::getDayEndTime()
        );
        $currentMonthConsumptionQuery = $this->getCurrentConsumpptionQuery(
            DateHelpers::getMonthStartTime(),
            DateHelpers::getMonthEndTime()
        );
        $currentYearConsumptionQuery = $this->getCurrentConsumpptionQuery(
            DateHelpers::getYearStartTime(),
            DateHelpers::getYearEndTime()
        );

        $results = DB::select(
            "select ($currentDayConsumptionQuery) as currentDayConsumption,
            ($currentMonthConsumptionQuery) as currentMonthConsumption,
            ($currentYearConsumptionQuery) as currentYearConsumption"
        );

        $formatedResults = [];

        $formatedResults['currentDayConsumption'] = number_format($results[0]->currentDayConsumption, 2);
        $formatedResults['currentMonthConsumption'] = number_format($results[0]->currentMonthConsumption, 2);
        $formatedResults['currentYearConsumption'] = number_format($results[0]->currentYearConsumption, 2);

        return $formatedResults;
    }

    public function getCurrentConsumpptionQuery(\DateTime $startDate, \DateTime $endDate): string
    {
        return Consumption::query()
            ->selectRaw('sum(amount)')
            ->whereRaw(
                "consumed_at between '$startDate' and '$endDate'",
            )->toSql();
    }
}
