<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface FilterContract
{
    public function handle(Builder $builder, Closure $next);
}
