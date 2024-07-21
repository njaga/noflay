<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\LandlordPayoutController;
use App\Http\Controllers\MandatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Controllers\DemoRequestController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TenantAccountController;

// Route pour la page de bienvenue disponible sans authentification
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Route pour déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route pour les demandes de démonstration
Route::post('/demo-request', [DemoRequestController::class, 'store']);

// Route pour le formulaire de contact
Route::post('/contact', [ContactController::class, 'send']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckUserStatus::class,
])->group(function () {
    // Routes protégées
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/landlords/{landlord}/account', [LandlordController::class, 'show'])->name('landlords.account.show');
    Route::get('/mandat', [MandatController::class, 'create'])->name('mandat.create');
    Route::post('/landlords/{id}/create-account', [LandlordController::class, 'createAccount'])->name('landlords.create-account');


    // Routes pour la gestion des propriétés
    Route::resource('properties', PropertyController::class);
    Route::get('properties/{property}/report', [PropertyController::class, 'report'])->name('properties.report');

    // Routes pour la gestion des locataires
    Route::resource('tenants', TenantController::class);
    Route::get('/tenants/{tenant}/lease-contracts/create', [TenantController::class, 'createLeaseContract'])->name('tenants.createLeaseContract');
    Route::post('/tenants/{id}/create-account', [TenantAccountController::class, 'createTenantAccount'])->name('tenants.create-account');

    // Routes pour la gestion des contrats
    Route::resource('contracts', ContractController::class);
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::get('/contracts/create/{tenantId}', [ContractController::class, 'createWithTenant'])->name('contracts.createWithTenant');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::post('contracts/{id}/restore', [ContractController::class, 'restore'])->name('contracts.restore');

    // Routes pour les utilisateurs désactivés
    Route::get('/inactive-user', function () {
        return Inertia::render('InactiveUser');
    })->name('inactive.user');

    // Routes pour les sessions expirées
    Route::get('/expired-session', function () {
        return Inertia::render('ExpiredSession');
    })->name('expired-session');

    // Routes pour la gestion des finances
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    // Routes pour la gestion des paiements
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('payments/{payment}/restore', [PaymentController::class, 'restore'])->name('payments.restore');

    // Routes pour la gestion des dépenses
    Route::resource('expenses', ExpenseController::class);

    // Routes pour la gestion des transactions
    Route::resource('transactions', TransactionController::class);

        // Routes pour la gestion des paiements des bailleurs
    Route::resource('landlord-payouts', LandlordPayoutController::class);
    Route::get('landlord-payouts/details/{id}', [LandlordPayoutController::class, 'getLandlordDetails'])->name('landlord-payouts.details');
    Route::get('/landlords/{id}/details', [LandlordPayoutController::class, 'getLandlordDetails'])->name('landlord-payouts.details');

});
