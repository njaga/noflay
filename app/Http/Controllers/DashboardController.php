<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return Inertia::render('Dashboard/SuperAdmin', [
                'companies' => Company::all(),
                'users' => User::all(),
                'companyStats' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    'data' => Company::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
                        ->groupBy('month')
                        ->pluck('count', 'month')
                        ->toArray(),
                ],
                'userTypes' => [
                    'labels' => ['Super Admin', 'Admin Entreprise', 'Utilisateur Entreprise'],
                    'data' => [
                        User::role('super_admin')->count(),
                        User::role('admin_entreprise')->count(),
                        User::role('user_entreprise')->count(),
                    ],
                ],
            ]);
        }

        if ($user->hasRole('admin_entreprise')) {
            $company = $user->company;

            return Inertia::render('Dashboard/AdminEntreprise', [
                'company' => $company,
                'users' => $company->users,
                'contracts' => Contract::where('company_id', $company->id)->get(),
                'properties' => Property::where('company_id', $company->id)->get(),
                'tenants' => Tenant::where('company_id', $company->id)->get(),
                'contractStats' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    'data' => Contract::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
                        ->where('company_id', $company->id)
                        ->groupBy('month')
                        ->pluck('count', 'month')
                        ->toArray(),
                ],
                'propertyTypes' => [
                    'labels' => ['Appartement', 'Maison', 'Magasin'],
                    'data' => [
                        Property::where('company_id', $company->id)->where('property_type', 'appartement')->count(),
                        Property::where('company_id', $company->id)->where('property_type', 'maison')->count(),
                        Property::where('company_id', $company->id)->where('property_type', 'magasin')->count(),
                    ],
                ],
            ]);
        }

        return Inertia::render('Dashboard/User');
    }
}
