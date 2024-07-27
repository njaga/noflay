<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->guestDashboard();
        }

        $role = $user->roles->first();
        $roleName = $role ? $role->name : null;

        switch ($roleName) {
            case 'super_admin':
                return $this->superAdminDashboard($user);
            case 'admin_entreprise':
                return $this->adminEntrepriseDashboard($user);
            case 'bailleur':
                return $this->bailleurDashboard($user);
            case 'locataire':
                return $this->locataireDashboard($user);
            default:
                return $this->defaultDashboard($user);
        }
    }

    private function guestDashboard()
    {
        return Inertia::render('Dashboard/Guest', [
            'message' => 'Veuillez vous connecter pour accéder à votre tableau de bord.',
        ]);
    }

    private function superAdminDashboard($user)
    {
        return Inertia::render('Dashboard/SuperAdmin', [
            'companies' => Company::all(),
            'users' => User::all(),
            'companyStats' => $this->getCompanyStats(),
            'userTypes' => $this->getUserTypes(),
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function adminEntrepriseDashboard($user)
    {
        $company = $user->company;

        if (!$company) {
            return Inertia::render('Dashboard/AdminEntrepriseIncomplete', [
                'message' => 'Votre profil d\'administrateur n\'est pas lié à une entreprise.',
                'auth' => $this->getAuthData($user),
            ]);
        }

        return Inertia::render('Dashboard/AdminEntreprise', [
            'company' => $company,
            'users' => $company->users,
            'contracts' => Contract::where('company_id', $company->id)->get(),
            'properties' => Property::where('company_id', $company->id)->get(),
            'tenants' => Tenant::where('company_id', $company->id)->get(),
            'contractStats' => $this->getContractStats($company->id),
            'propertyTypes' => $this->getPropertyTypes($company->id),
            'revenueStats' => $this->getRevenueStats($company->id),
            'occupancyRateStats' => $this->getOccupancyRateStats($company->id),
            'expenseDistributionStats' => $this->getExpenseDistributionStats($company->id),
            'userGrowthStats' => $this->getUserGrowthStats($company->id),
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function bailleurDashboard($user)
    {
        $landlord = $user->landlord;

        if (!$landlord) {
            return Inertia::render('Dashboard/BailleurIncomplete', [
                'message' => 'Votre profil de bailleur n\'est pas encore configuré.',
                'auth' => $this->getAuthData($user),
            ]);
        }

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

        $hasData = $properties->isNotEmpty() || $contracts->isNotEmpty() || $tenants->isNotEmpty() || $payments->isNotEmpty() || $expenses->isNotEmpty();

        if (!$hasData) {
            return Inertia::render('Dashboard/BailleurEmpty', [
                'message' => 'Aucune donnée disponible pour le moment.',
                'landlord' => $landlord,
                'auth' => $this->getAuthData($user),
            ]);
        }

        return Inertia::render('Dashboard/Bailleur', [
            'landlord' => $landlord,
            'properties' => $properties,
            'contracts' => $contracts,
            'tenants' => $tenants,
            'payments' => $payments,
            'expenses' => $expenses,
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function locataireDashboard($user)
    {
        return Inertia::render('Dashboard/Locataire', [
            'user' => $user,
            'contracts' => $user->contracts ?? [],
            'payments' => $user->payments ?? [],
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function defaultDashboard($user)
    {
        return Inertia::render('Dashboard/Default', [
            'message' => 'Bienvenue ! Votre rôle n\'a pas de tableau de bord spécifique.',
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function getAuthData($user)
    {
        return [
            'user' => [
                'name' => $user->name ?? 'Guest',
                'role' => $user->roles->first()->name ?? 'No Role',
            ],
        ];
    }

    private function getCompanyStats()
    {
        $stats = Company::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        $data = array_fill(0, 12, 0);
        foreach ($stats as $stat) {
            $data[$stat->month - 1] = $stat->count;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $data,
        ];
    }

    private function getUserTypes()
    {
        return [
            'labels' => ['Super Admin', 'Admin Entreprise', 'Utilisateur Entreprise'],
            'data' => [
                User::role('super_admin')->count(),
                User::role('admin_entreprise')->count(),
                User::role('user_entreprise')->count(),
            ],
        ];
    }

    private function getContractStats($companyId)
    {
        $stats = Contract::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = array_fill(0, 12, 0);

        foreach ($stats as $stat) {
            $data[$stat->month - 1] = $stat->count;
        }

        for ($i = 0; $i < 12; $i++) {
            $labels[] = Carbon::create()->month($i + 1)->format('M');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getPropertyTypes($companyId)
    {
        $types = Property::where('company_id', $companyId)
            ->selectRaw('property_type, COUNT(*) as count')
            ->groupBy('property_type')
            ->get();

        return [
            'labels' => $types->pluck('property_type'),
            'data' => $types->pluck('count'),
        ];
    }

    private function getRevenueStats($companyId)
    {
        $revenues = Payment::whereHas('contract', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($revenues as $revenue) {
            $data[$revenue->month - 1] = $revenue->total;
        }

        return [
            'data' => $data,
        ];
    }

    private function getOccupancyRateStats($companyId)
    {
        $occupancyRates = Property::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, AVG(CASE WHEN status = "occupied" THEN 1 ELSE 0 END) as rate')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($occupancyRates as $rate) {
            $data[$rate->month - 1] = round($rate->rate * 100, 2);
        }

        return [
            'data' => $data,
        ];
    }

    private function getExpenseDistributionStats($companyId)
    {
        $expenses = Expense::whereHas('property', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->get();

        return [
            'labels' => $expenses->pluck('type'),
            'data' => $expenses->pluck('total'),
        ];
    }

    private function getUserGrowthStats($companyId)
    {
        $userGrowth = User::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($userGrowth as $growth) {
            $data[$growth->month - 1] = $growth->count;
        }

        return [
            'data' => $data,
        ];
    }

}
