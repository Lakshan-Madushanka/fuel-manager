<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Actions\Dashboard\GetFuelConsumptionReport;
use App\Actions\Dashboard\GetTotalFuelConsumption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getFuelConsumptionReport(GetFuelConsumptionReport $consumptionReport, Request $request)
    {
        return $consumptionReport->execute($request);
    }

    public function getTotalFuelConsumption(GetTotalFuelConsumption $totalFuelConsumption)
    {
        return $totalFuelConsumption->execute();
    }
}
