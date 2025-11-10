<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/', 'welcome')->name('welcome');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('jobs', function () {
            return view('admin.jobs.index');
        })->name('jobs.index');
    });

    Route::prefix('carrier')->name('carrier.')->group(function () {
        Route::get('jobs', function () {
            return view('carrier.jobs.index');
        })->name('jobs.index');
    });
});
