<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
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

    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));

    // Routes pour Users
    Route::resource('users', UserController::class);
    Route::put('/users/toggle-status/{user}', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Routes pour la gestion des bailleurs
    Route::resource('landlords', LandlordController::class);

    // Routes pour la gestion des propriétés
    Route::resource('properties', PropertyController::class);

    // Routes pour la gestion des locataires
    Route::resource('tenants', TenantController::class);
    Route::get('/tenants/{tenant}/lease-contracts/create', [TenantController::class, 'createLeaseContract'])->name('tenants.createLeaseContract');

    // Routes pour la gestion des contrats
    Route::resource('contracts', ContractController::class);
    Route::get('/contracts/create/{tenantId}', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');

    // Routes pour les utilisateurs désactivés
    Route::get('/inactive-user', function () {
        return Inertia::render('InactiveUser');
    })->name('inactive.user');

    // Routes pour les sessions expirées
    Route::get('/expired-session', function () {
        return Inertia::render('ExpiredSession');
    })->name('expired-session');
});
