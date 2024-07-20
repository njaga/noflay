<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoRequestController;

Route::post('/demo-request', [DemoRequestController::class, 'store']);
