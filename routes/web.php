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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TenantAccountController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LandlordProfileController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TermsOfServiceController;
use App\Http\Middleware\CheckUserActivity;
use App\Http\Middleware\CheckSessionExpiration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

// Route pour la page de bienvenue disponible sans authentification
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Routes pour la politique de confidentialité et les conditions d'utilisation
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');
Route::get('/terms-of-service', [TermsOfServiceController::class, 'index'])->name('terms-of-service');

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

// Nouvelles routes pour la complétion du profil du bailleur
Route::get('/landlord/profile/complete', function () {
    return Inertia::render('Landlords/ProfileCompletion');
})->name('landlord.profile.show');

Route::post('/landlord/profile/complete', [LandlordController::class, 'completeProfile'])
    ->name('landlord.profile.complete');

        // Routes pour la gestion des propriétés
        Route::get('properties/archives', [PropertyController::class, 'archives'])->name('properties.archives');
        Route::post('properties/{id}/restore', [PropertyController::class, 'restore'])->name('properties.restore');
        Route::get('/properties/archived/{id}', [PropertyController::class, 'showArchived'])->name('properties.showArchived');
        Route::post('properties/{id}/force-delete', [PropertyController::class, 'forceDelete'])->name('properties.forceDelete');
        Route::resource('properties', PropertyController::class);
        Route::get('properties/{property}/report', [PropertyController::class, 'report'])->name('properties.report');
        Route::match(['put', 'post'], '/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');


        Route::get('tenants/archives', [TenantController::class, 'archives'])->name('tenants.archives');
        Route::post('tenants/{id}/restore', [TenantController::class, 'restore'])->name('tenants.restore');
        Route::delete('tenants/{id}/force-delete', [TenantController::class, 'forceDelete'])->name('tenants.forceDelete');
        Route::get('tenants/archived/{id}', [TenantController::class, 'showArchived'])->name('tenants.showArchived');

        // Utilisez Route::resource avec l'option 'except' pour exclure les routes que vous définissez manuellement
        Route::resource('tenants', TenantController::class);

        // Définissez manuellement la route de mise à jour pour s'assurer qu'elle utilise la méthode PATCH
        Route::patch('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');

        // Route pour la création de compte locataire
        Route::post('/tenants/{id}/create-account', [TenantAccountController::class, 'createTenantAccount'])->name('tenants.create-account');

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
        Route::get('/contracts/{contract}/generate', [ContractController::class, 'generateContract'])->name('contracts.generate');
        Route::post('/contracts/{contract}/upload-document', [ContractController::class, 'uploadDocument'])->name('contracts.upload-document');
        Route::get('/contracts/{contract}/download-document/{documentType}', [ContractController::class, 'downloadDocument'])
            ->name('contracts.download-document');
        Route::delete('/contracts/{contract}/delete-document/{documentType}', [ContractController::class, 'deleteDocument'])
            ->name('contracts.delete-document');

        // Routes pour la gestion des documents
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{contract}', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

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
        Route::get('/transactions/grand-livre', [TransactionController::class, 'grandLivre'])->name('transactions.grand-livre');
        Route::get('/transactions/ventilation', [TransactionController::class, 'ventilation'])->name('transactions.ventilation');

        // Routes pour la gestion des paiements des bailleurs
        Route::resource('landlord-payouts', LandlordPayoutController::class);
        Route::get('landlord-payouts/details/{id}', [LandlordPayoutController::class, 'getLandlordDetails'])->name('landlord-payouts.details');
        Route::get('/landlords/{id}/details', [LandlordPayoutController::class, 'getLandlordDetails'])->name('landlords.details');
        Route::get('/landlord-transactions/{id}/generate-receipt', [LandlordPayoutController::class, 'generateReceipt'])
        ->name('landlord-transactions.generate-receipt');


        Route::get('/help', function () {
            return Inertia::render('Help');
        })->name('help');
        Route::post('/help/send-email', [HelpController::class, 'sendEmail'])->name('help.send-email');
        Route::post('/help/request-callback', [HelpController::class, 'requestCallback'])->name('help.request-callback');

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

// Route pour la section À PROPOS DE NOFLAY
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

// API Paiement de Paytech
Route::post('/api/my-ipn', function () {
    $type_event = request('type_event');
    $custom_field = json_decode(request('custom_field'), true);
    $ref_command = request('ref_command');
    $item_name = request('item_name');
    $item_price = request('item_price');
    $devise = request('devise');
    $command_name = request('command_name');
    $env = request('env');
    $token = request('token');
    $api_key_sha256 = request('api_key_sha256');
    $api_secret_sha256 = request('api_secret_sha256');

    $my_api_key = env('PAYTECH_API_KEY');
    $my_api_secret = env('PAYTECH_API_SECRET');

    if (hash('sha256', $my_api_secret) === $api_secret_sha256 && hash('sha256', $my_api_key) === $api_key_sha256) {
        // Paiement confirmé par Paytech
        Log::info('Paiement Paytech confirmé', [
            'ref_command' => $ref_command,
            'item_name' => $item_name,
            'item_price' => $item_price,
            'devise' => $devise,
        ]);

        // Ajoutez ici la logique pour mettre à jour le statut de l'abonnement de l'utilisateur
        // Par exemple :
        // User::where('payment_ref', $ref_command)->update(['subscription_status' => 'active']);

        return response()->json(['status' => 'success']);
    } else {
        // La requête ne provient pas de Paytech
        Log::warning('Tentative de notification IPN non autorisée');
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }
});
