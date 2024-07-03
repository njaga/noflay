<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Company;
use App\Models\Property;
use App\Models\Tenant;
use App\Policies\UserPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\TenantPolicy;
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
    }
}
