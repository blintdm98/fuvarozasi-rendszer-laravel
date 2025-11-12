<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\Jobs\JobList;
use App\Livewire\Carrier\Jobs\MyJobs;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('home');
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('jobs', JobList::class)->name('jobs.index');
    });

    Route::prefix('carrier')->name('carrier.')->group(function () {
        Route::get('jobs', MyJobs::class)->name('jobs.index');
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
