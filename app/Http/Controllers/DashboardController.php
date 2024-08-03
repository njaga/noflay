<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Landlord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LandlordTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $recentProperties = Property::where('company_id', $company->id)
            ->select('id', 'name', 'address', 'property_type', 'price', 'available_count')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard/AdminEntreprise', [
            'company' => $company,
            'users' => $company->users,
            'contracts' => Contract::where('company_id', $company->id)->get(),
            'properties' => $recentProperties,
            'tenants' => Tenant::where('company_id', $company->id)->get(),
            'contractStats' => $this->getContractStats($company->id),
            'propertyTypes' => $this->getPropertyTypes($company->id),
            'revenueStats' => $this->getRevenueStats($company->id),
            'occupancyRateStats' => $this->getOccupancyRateStats($company->id),
            'expenseDistributionStats' => $this->getExpenseDistributionStats($company->id),
            'userGrowthStats' => $this->getUserGrowthStats($company->id),
            'landlordStats' => $this->getLandlordStats($company->id),
            'tenantStats' => $this->getTenantStats($company->id),
            'commissionStats' => $this->getCommissionStats($company->id),
            'latePaymentStats' => $this->getLatePaymentStats($company->id),
            'expenseStats' => $this->getExpenseStats($company->id),
            'auth' => $this->getAuthData($user),
        ]);
    }

    private function getLandlordStats($companyId)
    {
        $landlords = Landlord::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($landlords as $landlord) {
            $data[$landlord->month - 1] = $landlord->count;
        }

        return [
            'data' => $data,
        ];
    }

    private function getTenantStats($companyId)
    {
        $tenants = Tenant::where('company_id', $companyId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($tenants as $tenant) {
            $data[$tenant->month - 1] = $tenant->count;
        }

        return [
            'data' => $data,
        ];
    }

    private function getCommissionStats($companyId)
    {
        DB::enableQueryLog();

        $commissions = LandlordTransaction::whereHas('landlord', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(commission_amount) as total')
            )
            ->whereYear('transaction_date', date('Y'))
            ->whereNotNull('commission_amount')
            ->groupBy(DB::raw('YEAR(transaction_date)'), DB::raw('MONTH(transaction_date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        Log::info('SQL Query:', [DB::getQueryLog()[0]['query'], DB::getQueryLog()[0]['bindings']]);
        Log::info('Commissions data:', $commissions->toArray());

        $data = array_fill(0, 12, 0);

        foreach ($commissions as $commission) {
            $data[$commission->month - 1] = $commission->total;
        }

        $result = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => $data,
        ];

        Log::info('Final result:', $result);

        return $result;
    }

    private function getLatePaymentStats($companyId)
    {
        DB::enableQueryLog();

        $latePayments = DB::table('contracts')
            ->join('payments', 'contracts.id', '=', 'payments.contract_id')
            ->where('contracts.company_id', $companyId)
            ->whereRaw('YEAR(payments.payment_date) = ?', [date('Y')])
            ->whereRaw('payments.payment_date > DATE_ADD(DATE_ADD(LAST_DAY(DATE_SUB(payments.payment_date, INTERVAL 1 MONTH)), INTERVAL 1 DAY), INTERVAL 5 DAY)')
            ->selectRaw('MONTH(payments.payment_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        Log::info('SQL Query:', [DB::getQueryLog()[0]['query'], DB::getQueryLog()[0]['bindings']]);
        Log::info('Late Payments data:', $latePayments->toArray());

        $data = array_fill(0, 12, 0);

        foreach ($latePayments as $payment) {
            $data[$payment->month - 1] = $payment->count;
        }

        $result = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => $data,
        ];

        Log::info('Final result:', $result);

        return $result;
    }

    private function getExpenseStats($companyId)
    {
        $expenses = Expense::whereHas('property', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->whereYear('expense_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(0, 12, 0);

        foreach ($expenses as $expense) {
            $data[$expense->month - 1] = $expense->total;
        }

        return [
            'data' => $data,
        ];
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
        DB::enableQueryLog();

        $totalProperties = Property::where('company_id', $companyId)->count();

        $occupancyRates = DB::table('properties')
            ->leftJoin('contracts', 'properties.id', '=', 'contracts.property_id')
            ->where('properties.company_id', $companyId)
            ->whereRaw('YEAR(COALESCE(contracts.start_date, CURDATE())) = ?', [date('Y')])
            ->selectRaw('
                MONTH(COALESCE(contracts.start_date, CURDATE())) as month,
                COUNT(DISTINCT properties.id) as total_properties,
                SUM(CASE
                    WHEN contracts.id IS NOT NULL
                    AND (contracts.end_date IS NULL OR contracts.end_date >= LAST_DAY(COALESCE(contracts.start_date, CURDATE())))
                    THEN 1 ELSE 0
                END) as occupied_properties
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        Log::info('SQL Query:', [DB::getQueryLog()[0]['query'], DB::getQueryLog()[0]['bindings']]);
        Log::info('Occupancy Rates data:', $occupancyRates->toArray());

        $data = array_fill(0, 12, 0);

        foreach ($occupancyRates as $rate) {
            $occupancyRate = ($rate->occupied_properties / $totalProperties) * 100;
            $data[$rate->month - 1] = round($occupancyRate, 2);
        }

        $result = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => $data,
        ];

        Log::info('Final result:', $result);

        return $result;
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
