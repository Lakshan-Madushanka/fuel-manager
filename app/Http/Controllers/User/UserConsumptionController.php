<?php

namespace App\Http\Controllers\User;

use App\Actions\UserConsumption\CreateNewConsumption;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelConsumeRequest;
use App\Models\Consumption;
use App\Models\User;

class UserConsumptionController extends Controller
{
    public function index(User $user)
    {
        $consumptions = $user->consumptions()->cursorPaginate();

        return view('user.consumption.show', compact('consumptions'));
    }

    public function store(int $userId, FuelConsumeRequest $request, CreateNewConsumption $store)
    {
        $store->execute($userId, $request);

        return back();
    }
}
