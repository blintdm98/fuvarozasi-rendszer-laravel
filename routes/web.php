<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Admin\Jobs\AssignJob;
use App\Livewire\Admin\Jobs\JobForm;
use App\Livewire\Admin\Jobs\JobList;
use App\Livewire\Carrier\Jobs\MyJobs;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.login');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('jobs', JobList::class)->name('jobs.index');
        Route::get('jobs/create', JobForm::class)->name('jobs.create');
        Route::get('jobs/{job}/edit', JobForm::class)->name('jobs.edit');
        Route::get('jobs/{job}/assign', AssignJob::class)->name('jobs.assign');
    });

    Route::prefix('carrier')->name('carrier.')->group(function () {
        Route::get('jobs', MyJobs::class)->name('jobs.index');
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
