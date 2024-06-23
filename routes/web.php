<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\PropertyController;
use App\Http\Middleware\CheckUserStatus;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckUserStatus::class,  // Application du middleware ici
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Routes pour Companies
    Route::resource('companies', CompanyController::class);
    Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');

    // Routes pour Users
    Route::resource('users', UserController::class);

    // Routes pour la gestion des bailleurs
    Route::middleware('role:super_admin,admin_entreprise,user_entreprise')->group(function () {
        Route::resource('landlords', LandlordController::class);
    });

    // Routes pour la gestion des propriétés
    Route::resource('properties', PropertyController::class);

    // Routes pour les utilisateurs désactivés
    Route::get('/inactive-user', function () {
        return Inertia::render('InactiveUser');
    })->name('inactive.user');

    // Routes pour les sessions expirées
    Route::get('/expired-session', function () {
        return Inertia::render('ExpiredSession');
    })->name('expired-session');
});
