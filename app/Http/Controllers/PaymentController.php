<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Contract;
use App\Models\Tenant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    const TVA_RATE = 0.18; // 18% TVA

    public function index()
    {
        Gate::authorize('viewAny', Payment::class);

        $query = Payment::with(['tenant', 'contract']);

        if (!Auth::user()->hasRole('super_admin')) {
            $query->whereHas('tenant', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        }

        $payments = $query->paginate(10);

        return Inertia::render('Payments/Index', [
            'payments' => $payments,
            'tenants' => Tenant::all(),
            'contracts' => Contract::all(),
            'auth' => [
                'user' => auth()->user(),
                'roles' => auth()->user()->roles->pluck('name'),
            ],
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Payment::class);

        return Inertia::render('Payments/Create', [
            'tenants' => Tenant::all(),
            'contracts' => Contract::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Payment::class);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_month' => 'required|date_format:Y-m',
            'payment_type' => 'required|in:integral,advance,balance',
        ]);

        // Calculer la TVA
        $tva_amount = $validated['amount'] * self::TVA_RATE;
        $total_amount = $validated['amount'] + $tva_amount;

        // Ajouter la TVA et le montant total
        $validated['tva_amount'] = $tva_amount;
        $validated['total_amount'] = $total_amount;
        $validated['company_id'] = Auth::user()->company_id;

        Log::info('Données validées:', $validated);

        DB::beginTransaction();

        try {
            $payment = Payment::create($validated);

            $contract = Contract::findOrFail($validated['contract_id']);

            $transaction = Transaction::create([
                'company_id' => Auth::user()->company_id,
                'payment_id' => $payment->id,
                'date' => $payment->payment_date,
                'amount' => $total_amount,
                'type' => 'PAYMENT',
                'description' => 'Paiement de loyer',
                'property_id' => $contract->property_id,
                'landlord_id' => $contract->property->landlord_id,
                'tenant_id' => $payment->tenant_id,
                'contract_id' => $payment->contract_id,
            ]);

            DB::commit();

            return redirect()->route('payments.index')->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'enregistrement du paiement:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement du paiement: ' . $e->getMessage()]);
        }
    }

    public function show(Payment $payment)
    {
        Gate::authorize('view', $payment);

        $contract = $payment->contract;
        $tenant = $contract ? $contract->tenant : null;
        $property = $contract ? $contract->property : null;

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
            'contract' => $contract,
            'tenant' => $tenant ? [
                'id' => $tenant->id,
                'first_name' => $tenant->first_name,
                'last_name' => $tenant->last_name,
                // Ajoutez d'autres champs du locataire si nécessaire
            ] : null,
            'property' => $property ? [
                'id' => $property->id,
                'name' => $property->name,
                // Ajoutez d'autres champs de la propriété si nécessaire
            ] : null,
        ]);
    }

    public function edit(Payment $payment)
    {
        Gate::authorize('update', $payment);

        return Inertia::render('Payments/Edit', [
            'payment' => $payment,
            'tenants' => Tenant::all(),
            'contracts' => Contract::all(),
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        Gate::authorize('update', $payment);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_month' => 'required|date_format:Y-m',
            'payment_type' => 'required|string|in:integral,advance,balance',
        ]);

        // Calculer la TVA
        $tva_amount = $validated['amount'] * self::TVA_RATE;
        $total_amount = $validated['amount'] + $tva_amount;

        // Ajouter la TVA et le montant total
        $validated['tva_amount'] = $tva_amount;
        $validated['total_amount'] = $total_amount;

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Payment $payment)
    {
        Gate::authorize('delete', $payment);

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Paiement supprimé avec succès.');
    }
}
