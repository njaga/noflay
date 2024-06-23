<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LandlordController extends Controller
{
    /**
     * Display a listing of the landlords.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $landlords = [];

        if (in_array($user->role, ['super_admin', 'admin_entreprise', 'user_entreprise'])) {
            if ($user->role === 'super_admin') {
                $landlords = Landlord::with('company')->get();
            } else {
                $landlords = Landlord::where('company_id', $user->company_id)
                                     ->with('company')
                                     ->get();
            }
        }

        return Inertia::render('Landlords/Index', [
            'landlords' => $landlords,
        ]);
    }

    /**
     * Show the form for creating a new landlord.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $user = Auth::user();
        $companies = [];

        if ($user->role === 'super_admin') {
            $companies = Company::all();
        } else {
            $companies = [$user->company];
        }

        return Inertia::render('Landlords/Create', [
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created landlord in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prenom' => 'required|string|max:191',
            'nom' => 'required|string|max:191',
            'telephone' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:landlords',
            'adresse' => 'required|string|max:191',
            'numero_cni_passport' => 'required|string|max:191',
            'date_expiration' => 'required|date',
            'pourcentage_agence' => 'required|numeric|min:0|max:100',
            'company_id' => 'required|exists:companies,id',
        ]);

        if (!$this->canPerformAction($user->company_id)) {
            abort(403, 'Unauthorized action.');
        }

        $landlord = Landlord::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'numero_cni_passport' => $request->numero_cni_passport,
            'date_expiration' => $request->date_expiration,
            'pourcentage_agence' => $request->pourcentage_agence,
            'company_id' => $user->company_id,
        ]);

        return redirect()->route('landlords.index')->with('success', 'Bailleur créé avec succès.');
    }

    /**
     * Display the specified landlord.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return \Inertia\Response
     */
    public function show(Landlord $landlord)
    {
        if (!$this->canViewLandlord($landlord)) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Landlords/Show', [
            'landlord' => $landlord->load('company'),
        ]);
    }

    /**
     * Show the form for editing the specified landlord.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return \Inertia\Response
     */
    public function edit(Landlord $landlord)
    {
        if (!$this->canEditLandlord($landlord)) {
            abort(403, 'Unauthorized action.');
        }

        $companies = [];
        $currentUser = Auth::user();

        if ($currentUser->role === 'super_admin') {
            $companies = Company::all();
        } else {
            $companies = [$currentUser->company];
        }

        return Inertia::render('Landlords/Edit', [
            'landlord' => $landlord,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified landlord in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Landlord  $landlord
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Landlord $landlord)
    {
        if (!$this->canEditLandlord($landlord)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'prenom' => 'required|string|max:191',
            'nom' => 'required|string|max:191',
            'telephone' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:landlords,email,' . $landlord->id,
            'adresse' => 'required|string|max:191',
            'numero_cni_passport' => 'required|string|max:191',
            'date_expiration' => 'required|date',
            'pourcentage_agence' => 'required|numeric|min:0|max:100',
            'company_id' => $request->company_id,
        ]);

        if (!$this->canPerformAction($request->user()->company_id)) {
            abort(403, 'Unauthorized action.');
        }

        $landlord->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'numero_cni_passport' => $request->numero_cni_passport,
            'date_expiration' => $request->date_expiration,
            'pourcentage_agence' => $request->pourcentage_agence,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('landlords.index')->with('success', 'Bailleur mis à jour avec succès.');
    }

    /**
     * Remove the specified landlord from storage.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Landlord $landlord)
    {
        if (!$this->canDeleteLandlord($landlord)) {
            abort(403, 'Unauthorized action.');
        }

        $landlord->delete();

        return redirect()->route('landlords.index')->with('success', 'Bailleur supprimé avec succès.');
    }

    /**
     * Check if the current user can perform the action.
     *
     * @param int $companyId
     * @return bool
     */
    private function canPerformAction($companyId)
    {
        $user = Auth::user();
        if ($user->role === 'super_admin') {
            return true;
        } elseif (in_array($user->role, ['admin_entreprise', 'user_entreprise'])) {
            return $user->company_id === $companyId;
        }
        return false;
    }

    /**
     * Check if the current user can view the specified landlord.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return bool
     */
    private function canViewLandlord(Landlord $landlord)
    {
        $currentUser = Auth::user();
        if ($currentUser->role === 'super_admin') {
            return true;
        } elseif (in_array($currentUser->role, ['admin_entreprise', 'user_entreprise'])) {
            return $currentUser->company_id === $landlord->company_id;
        }
        return false;
    }

    /**
     * Check if the current user can edit the specified landlord.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return bool
     */
    private function canEditLandlord(Landlord $landlord)
    {
        return $this->canViewLandlord($landlord);
    }

    /**
     * Check if the current user can delete the specified landlord.
     *
     * @param  \App\Models\Landlord  $landlord
     * @return bool
     */
    private function canDeleteLandlord(Landlord $landlord)
    {
        return $this->canViewLandlord($landlord);
    }
}
