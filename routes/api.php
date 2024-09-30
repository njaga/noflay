<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\TransactionController;

Route::post('/demo-request', [DemoRequestController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/transactions/export', [TransactionController::class, 'export']);
});
