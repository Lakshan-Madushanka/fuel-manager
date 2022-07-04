<?php

namespace App\Filters\User;

use App\Filters\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class Search implements FilterContract
{
    public function handle(Builder $builder, \Closure $next)
    {
        request()->whenFilled(
            'search',
            fn () => $next($builder->where('nic', request()->search))
        );

        return $next($builder);
    }
}
