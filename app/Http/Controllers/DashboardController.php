<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $this->superAdminDashboard();
        }

        if ($user->hasRole('admin_entreprise')) {
            return $this->adminEntrepriseDashboard($user);
        }

        if ($user->hasRole('bailleur')) {
            return $this->bailleurDashboard($user);
        }

        if ($user->hasRole('locataire')) {
            return $this->locataireDashboard($user);
        }

        // Default case: user has no recognized role
        return $this->defaultDashboard();
    }

    private function superAdminDashboard()
    {
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

    private function adminEntrepriseDashboard($user)
    {
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

    private function bailleurDashboard($user)
    {
        // Vérifier si l'utilisateur a un bailleur associé
        $landlord = $user->landlord;

        if (!$landlord) {
            // Si aucun bailleur n'est associé, rendre une vue spéciale au lieu de rediriger
            return Inertia::render('Dashboard/BailleurIncomplete', [
                'message' => 'Votre profil de bailleur n\'est pas encore configuré.',
                'user' => $user
            ]);
        }

        // Récupérer les données avec des vérifications supplémentaires
        $properties = Property::where('landlord_id', $landlord->id)->get();
        $contracts = Contract::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->get();
        $tenants = Tenant::whereHas('properties', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->get();
        $payments = Payment::whereHas('contract.property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->get();
        $expenses = Expense::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->get();

        // Vérifier si des données existent
        $hasData = $properties->isNotEmpty() || $contracts->isNotEmpty() || $tenants->isNotEmpty() || $payments->isNotEmpty() || $expenses->isNotEmpty();

        if (!$hasData) {
            // Si aucune donnée n'existe, rendre une vue spéciale
            return Inertia::render('Dashboard/BailleurEmpty', [
                'message' => 'Aucune donnée disponible pour le moment.',
                'landlord' => $landlord
            ]);
        }

        // Si tout est en ordre, rendre le tableau de bord normal
        return Inertia::render('Dashboard/Bailleur', [
            'landlord' => $landlord,
            'properties' => $properties,
            'contracts' => $contracts,
            'tenants' => $tenants,
            'payments' => $payments,
            'expenses' => $expenses,
        ]);
    }

    private function locataireDashboard($user)
    {
        // Implement logic for tenant dashboard
        return Inertia::render('Dashboard/Locataire', [
            // Add necessary data for the tenant dashboard
            'user' => $user,
            'contracts' => $user->contracts,
            'payments' => $user->payments,
            // Add more relevant data as needed
        ]);
    }

    private function defaultDashboard()
    {
        // Render a default dashboard or show an error message
        return Inertia::render('Dashboard/Default', [
            'message' => 'Bienvenue ! Votre rôle n\'a pas de tableau de bord spécifique.',
            'user' => auth()->user()
        ]);
    }
}
