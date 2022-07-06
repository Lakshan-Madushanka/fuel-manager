<?php

namespace App\Http\Controllers\FrontEnd\Admin\Dashboard;

use App\Actions\Dashboard\GetCurrentFuelConsumptionReport;
use App\Actions\Dashboard\GetFuelConsumptionReport;
use App\Actions\Dashboard\GetTotalFuelConsumption;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke(
        GetFuelConsumptionReport $consumptionReport,
        GetTotalFuelConsumption $totalFuelConsumption,
        GetCurrentFuelConsumptionReport $currentFuelConsumptionReport
    ): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application {
        $yearlyConsumption = $consumptionReport->execute('yearly');
        $monthlyConsumption = $consumptionReport->execute('monthly');
        $weeklyConsumption = $consumptionReport->execute('weekly');
        $totalConsumption = $totalFuelConsumption->execute();
        $currentConsumption = $currentFuelConsumptionReport->execute();

        return view(
            'dashboard',
            compact(
                'yearlyConsumption',
                'monthlyConsumption',
                'weeklyConsumption',
                'totalConsumption',
                'currentConsumption',
            )
        );
    }
}
