<?php

namespace App\Actions\Dashboard;

use App\Filters\Consumption\DateRange;
use App\Models\Consumption;
use Illuminate\Pipeline\Pipeline;

class GetTotalFuelConsumption
{
    public function execute(): float
    {
        $query = app(Pipeline::class)
            ->send(Consumption::query())
            ->through([
                DateRange::class,
            ])
            ->thenReturn();

        return  $query->sum('amount');
    }
}
