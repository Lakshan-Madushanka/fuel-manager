<?php

namespace App\Actions\Dashboard;

use App\Filters\Consumption\DateRange;
use App\Models\Consumption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pipeline\Pipeline;

class GetFuelConsumptionReport
{
    public function execute(?string $basis = null): array|Collection
    {
        $data = [];

        $query = app(Pipeline::class)
            ->send(Consumption::query())
            ->through([
                DateRange::class,
            ])
            ->thenReturn();

        $dateFormat = $basis ? $this->getDateFormat($basis) : '%y-%m-%d';

        $results = $query->selectRaw("DATE_FORMAT(consumed_at, '$dateFormat') as date, sum(amount) as consumption")
            ->groupByRaw("(DATE_FORMAT(consumed_at, '$dateFormat'))")
            ->orderByRaw("(DATE_FORMAT(consumed_at, '$dateFormat')) desc")
            ->get();

        if (! request()->wantsJson()) {
            return  $results;
        }

        $data['dates'] = $results->pluck('date');
        $data['consumptions'] = $results->pluck('consumption');

        return $data;
    }

    public function getDateFormat(string $basis): string
    {
        return match ($basis) {
            'yearly' => "%Y",
            'monthly' => "%Y-%m",
            'weekly' => "%Y-%v",
            default => '%y-%m-%d'
        };
    }
}
