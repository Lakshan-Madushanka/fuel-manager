<?php

use App\Http\Controllers\Api\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Api\Admin\User\UserConsumptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([
    'auth:sanctum',
])->name('api.admin.')->group(function () {
    // fuel consumptions
    Route::resource('users.consumptions', UserConsumptionController::class)->only(['store']);
});

Route::prefix('admin')->name('api.admin.dashboard.')->group(function () {
    Route::get(
        '/fuel-consumption-report',
        [DashboardController::class, 'getFuelConsumptionReport']
    )->name('getFuelConsumptionReport');
    Route::get(
        '/total-fuel-consumption',
        [DashboardController::class, 'getTotalFuelConsumption']
    )->name('getTotalFuelConsumption');
    Route::get(
        '/current-fuel-consumption-report',
        [DashboardController::class, 'getCurrentFuelConsumption']
    )->name('getCurrentFuelConsumption');
});
