<?php

use App\Http\Controllers\Api\JobController;
use Illuminate\Support\Facades\Route;

Route::prefix('jobs')->group(function () {
    Route::post('/', [JobController::class, 'store']);
    Route::put('{job}', [JobController::class, 'update']);
    Route::patch('{job}/status', [JobController::class, 'updateStatus']);
});

