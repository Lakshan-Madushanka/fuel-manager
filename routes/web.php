<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\User\UserConsumptionController;
use App\Http\Controllers\User\UserController;
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

//users
Route::resource('users', UserController::class)->only('index');

// fuel consumptions
Route::resource('users.consumptions', UserConsumptionController::class)->only(['index', 'show', 'store']);

// Admin dashboard
Route::prefix('admin')->name('admin.dashboard')->group(function () {
    Route::get('/dashboard', DashboardController::class);
});