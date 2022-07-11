<?php

use App\Http\Controllers\FrontEnd\Admin\Account\AccountController;
use App\Http\Controllers\FrontEnd\Admin\Dashboard\DashboardController;
use App\Http\Controllers\FrontEnd\Admin\Qouta\QuotaController;
use App\Http\Controllers\FrontEnd\Admin\User\UserController;
use App\Http\Controllers\FrontEnd\User\UserConsumptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    return view('account.not-approved');
});

//Acount
Route::prefix('account')->name('account.')->group(function () {
    Route::get('/not-approved', function () {
        return view('account.not-approved');
    })->name('notApproved');
});

Route::middleware(['redirectAuthUser'])->get('/auth-redirect', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    'blockAccess',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        //account
        Route::post('account/{user}/approve', [AccountController::class, 'approve'])->name('account.approve');
        Route::post('account/{user}/block', [AccountController::class, 'block'])->name('account.block');

        //users
        Route::resource('users', UserController::class)->only(['index', 'destroy']);
        Route::post('users/mass-delete', [UserController::class, 'massDelete'])->name('users.massDelete');

        //users with current PlanFuel Consumption
        Route::get('users/consumptions', [UserController::class, 'withCurrentPlanFuelConsumption'])
    ->name('users.withCurrentPlanFuelConsumption');

        /*//Admin Dashboard
        Route::get('/dashboard', DashboardController::class)->name('dashboard');*/

        //quota
        Route::resource('/quotas', QuotaController::class)->only(['index', 'store', 'create']);

        //Route::resource('/users', QuotaController::class)->only(['index']);
    });

// fuel consumption
Route::middleware([
    'auth:sanctum',
])->resource('users.consumptions', UserConsumptionController::class)
    ->only(['index']);
