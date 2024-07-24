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
use App\Http\Controllers\HelpController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\CheckUserActivity;
use App\Http\Middleware\CheckSessionExpiration;
use Illuminate\Support\Facades\Auth;

// Route pour la page de bienvenue disponible sans authentification
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Ajout de la route de connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route pour déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route pour les demandes de démonstration
Route::post('/demo-request', [DemoRequestController::class, 'store']);

// Route pour le formulaire de contact
Route::post('/contact', [ContactController::class, 'send']);

// Routes nécessitant authentification et vérification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware([
        config('jetstream.auth_session'),
        CheckUserStatus::class,
        CheckSessionExpiration::class,
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
        Route::get('landlords/archives', [LandlordController::class, 'archives'])->name('landlords.archives');
        Route::post('landlords/{id}/restore', [LandlordController::class, 'restore'])->name('landlords.restore');
        Route::post('landlords/{id}/force-delete', [LandlordController::class, 'forceDelete'])->name('landlords.forceDelete');
        Route::get('landlords/archived/{id}', [LandlordController::class, 'showArchived'])->name('landlords.showArchived');
        Route::get('landlords/{landlord}/account', [LandlordController::class, 'show'])->name('landlords.account.show');
        Route::post('landlords/{id}/create-account', [LandlordController::class, 'createAccount'])->name('landlords.create-account');
        Route::get('/mandat', [MandatController::class, 'create'])->name('mandat.create');
        Route::resource('landlords', LandlordController::class);

        // Routes pour la gestion des propriétés
        Route::get('properties/archives', [PropertyController::class, 'archives'])->name('properties.archives');
        Route::post('properties/{id}/restore', [PropertyController::class, 'restore'])->name('properties.restore');
        Route::get('/properties/archived/{id}', [PropertyController::class, 'showArchived'])->name('properties.showArchived');
        Route::post('properties/{id}/force-delete', [PropertyController::class, 'forceDelete'])->name('properties.forceDelete');
        Route::resource('properties', PropertyController::class);
        Route::get('properties/{property}/report', [PropertyController::class, 'report'])->name('properties.report');

        // Routes pour la gestion des locataires
        Route::get('tenants/archives', [TenantController::class, 'archives'])->name('tenants.archives');
        Route::post('tenants/{id}/restore', [TenantController::class, 'restore'])->name('tenants.restore');
        Route::post('tenants/{id}/force-delete', [TenantController::class, 'forceDelete'])->name('tenants.forceDelete');
        Route::get('tenants/archived/{id}', [TenantController::class, 'showArchived'])->name('tenants.showArchived');
        Route::resource('tenants', TenantController::class);

        // Routes pour la gestion des contrats
        Route::post('contracts/{contract}/restore', [ContractController::class, 'restore'])->name('contracts.restore');
        Route::post('contracts/{contract}/force-delete', [ContractController::class, 'forceDelete'])->name('contracts.forceDelete');
        Route::get('contracts/archives', [ContractController::class, 'archives'])->name('contracts.archives');
        Route::get('/contracts/archived/{id}', [ContractController::class, 'showArchived'])->name('contracts.showArchived');
        Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
        Route::get('/contracts/create/{tenantId}', [ContractController::class, 'createWithTenant'])->name('contracts.createWithTenant');
        Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
        Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
        Route::resource('contracts', ContractController::class)->except(['create', 'store', 'edit', 'update']);

        // Routes pour les utilisateurs désactivés
        Route::get('/inactive-user', function () {
            return Inertia::render('Error', [
                'status' => 403,
                'message' => 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.'
            ]);
        })->name('inactive.user');

        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
        Route::get('/landlords/{id}/details', [LandlordPayoutController::class, 'getLandlordDetails'])->name('landlords.details');

        Route::get('/help', function () {
            return Inertia::render('Help');
        })->name('help');

        Route::get('/search', [SearchController::class, 'search'])->name('search');
    });
});

// Gestion des erreurs globales
Route::fallback(function () {
    return Inertia::render('Error', [
        'status' => 404,
        'message' => 'Page non trouvée.'
    ]);
});
