<?php

namespace App\Actions\User;

use App\Filters\User\Search;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pipeline\Pipeline;

class GetAllUsersWithCurrentPlanFuelConsumption
{
    public function execute(): Paginator
    {
        $query = app(Pipeline::class)
            ->send(User::query())
            ->through([
                Search::class,
            ])
            ->thenReturn();

        $users = $query->select(['id', 'nic', 'name', 'type'])
            ->currentPlanFuelConsumption()
            ->paginate();

        return $users;
    }
}
