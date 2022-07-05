<?php

namespace App\Http\Controllers\Api\User;

use App\Actions\UserConsumption\CreateNewConsumption;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelConsumeRequest;
use App\Models\Consumption;

class UserConsumptionController extends Controller
{

    public function store(int $userId, FuelConsumeRequest $request, CreateNewConsumption $store)
    {
        $store->execute($userId, $request);
    }
}
