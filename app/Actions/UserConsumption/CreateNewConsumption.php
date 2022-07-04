<?php

namespace App\Actions\UserConsumption;

use App\Exceptions\CouldNotConsumeFuel;
use App\Http\Requests\FuelConsumeRequest;
use App\Models\User;
use App\Services\UserFuelConsumptionService;

class CreateNewConsumption
{
    public function execute(int $userId, FuelConsumeRequest $request)
    {
        $user = User::query()
            ->select('id', 'type')
            ->whereId($userId)
            ->currentPlanFuelConsumption()
            ->firstOrFail();

        $amount = $request->validated()['amount'];

        $ufcs = new UserFuelConsumptionService($user);

        if ($ufcs->isFuelQuotaLimitExceeded($amount)) {
            $remainingAmount = $ufcs->getRemainingFuelQuota();

            throw CouldNotConsumeFuel::AllowedQuotaLimitExceeded($remainingAmount);
        }

        $user->consumptions()->create(['amount' => $amount]);
    }
}
