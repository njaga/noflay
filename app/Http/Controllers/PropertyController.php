<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $properties = [];

        if (in_array($user->role, ['super_admin', 'admin_entreprise', 'user_entreprise'])) {
            if ($user->role === 'super_admin') {
                $properties = Property::with('company')->get();
            } else {
                $properties = Property::where('company_id', $user->company_id)
                                      ->with('company')
                                      ->get();
            }
        }

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
        ]);
    }

    /**
     * Show the form for creating a new property.
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

        return Inertia::render('Properties/Create', [
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|string|in:Appartement,Studio,Villa,Terraine',
            'address' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
        ]);

        if (!$this->canPerformAction($user, $validatedData['company_id'])) {
            abort(403, 'Unauthorized action.');
        }

        Property::create($validatedData);

        return redirect()->route('properties.index')->with('success', 'Propriété créée avec succès.');
    }

    /**
     * Display the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Inertia\Response
     */
    public function show(Property $property)
    {
        if (!$this->canViewProperty($property)) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Properties/Show', [
            'property' => $property->load('company'),
        ]);
    }

    /**
     * Show the form for editing the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Inertia\Response
     */
    public function edit(Property $property)
    {
        if (!$this->canEditProperty($property)) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $companies = $user->role === 'super_admin' ? Company::all() : [$user->company];

        return Inertia::render('Properties/Edit', [
            'property' => $property,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Property $property)
    {
        if (!$this->canEditProperty($property)) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|string|in:Appartement,Studio,Villa,Terraine',
            'address' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
        ]);

        if (!$this->canPerformAction(Auth::user(), $validatedData['company_id'])) {
            abort(403, 'Unauthorized action.');
        }

        $property->update($validatedData);

        return redirect()->route('properties.index')->with('success', 'Propriété mise à jour avec succès.');
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Property $property)
    {
        if (!$this->canDeleteProperty($property)) {
            abort(403, 'Unauthorized action.');
        }

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Propriété supprimée avec succès.');
    }

    /**
     * Check if the current user can perform the action.
     *
     * @param \App\Models\User $user
     * @param int $companyId
     * @return bool
     */
    private function canPerformAction($user, $companyId)
    {
        if ($user->role === 'super_admin') {
            return true;
        } elseif (in_array($user->role, ['admin_entreprise', 'user_entreprise'])) {
            return $user->company_id === $companyId;
        }
        return false;
    }

    /**
     * Check if the current user can view the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return bool
     */
    private function canViewProperty(Property $property)
    {
        $user = Auth::user();
        if ($user->role === 'super_admin') {
            return true;
        } elseif (in_array($user->role, ['admin_entreprise', 'user_entreprise'])) {
            return $user->company_id === $property->company_id;
        }
        return false;
    }

    /**
     * Check if the current user can edit the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return bool
     */
    private function canEditProperty(Property $property)
    {
        return $this->canViewProperty($property);
    }

    /**
     * Check if the current user can delete the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return bool
     */
    private function canDeleteProperty(Property $property)
    {
        return $this->canViewProperty($property);
    }
}
