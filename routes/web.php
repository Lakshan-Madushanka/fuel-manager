<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'redirectAuthUser',
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    //users
    Route::resource('users', UserController::class)->only('index');

    //Admin Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //quota
    Route::resource('/quotas', QuotaController::class)->only(['index', 'store', 'create']);

});

// fuel consumption
Route::resource('users.consumptions', UserConsumptionController::class)
    ->scoped()
    ->only(['index']);

