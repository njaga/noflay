<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\LandlordPayout;
use App\Models\LandlordTransaction;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use App\Policies\UserPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ContractPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\LandlordPayoutPolicy;
use App\Policies\LandlordPolicy;
use App\Policies\LandlordTransactionPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\TenantPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(Contract::class, ContractPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(LandlordPayout::class, LandlordPayoutPolicy::class);
        Gate::policy(LandlordTransaction::class, LandlordTransactionPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);

    }
}
