<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LandlordProfileController extends Controller
{
    public function show()
    {
        return Inertia::render('Landlord/ProfileCompletion');
    }

    public function complete(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tax_id' => 'required|string|max:50',
            'bank_info' => 'required|string',
        ]);

        $user = $request->user();
        $landlord = $user->landlord ?? $user->landlord()->create();

        $landlord->update($validated);

        return redirect()->route('dashboard')->with('success', 'Profil de bailleur complété avec succès.');
    }
}
