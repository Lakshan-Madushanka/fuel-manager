<?php

namespace App\Filters\Consumption;

use App\Filters\FilterContract;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class DateRange implements FilterContract
{
    public function handle(Builder $builder, Closure $next)
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;

        if ($startDate && $endDate) {
            $next($builder->whereBetween('consumed_at', [$startDate, $endDate]));
        }

        return $next($builder);
    }
}
