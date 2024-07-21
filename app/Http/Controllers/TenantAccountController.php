<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Mail\NewTenantAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TenantAccountController extends Controller
{
    public function createTenantAccount($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);

            // Check if the tenant already has a user account
            if ($tenant->user) {
                return response()->json(['error' => 'Le locataire ' . $tenant->first_name . ' ' . $tenant->last_name . ' a déjà un compte.'], 400);
            }

            // Check if the email is already used
            if (User::where('email', $tenant->email)->exists()) {
                return response()->json(['error' => 'Le locataire ' . $tenant->first_name . ' ' . $tenant->last_name . ' a déjà un compte.'], 400);
            }

            // Generate a simple but secure password
            $password = Str::random(8);

            // Create the user account for the tenant
            $user = User::create([
                'name' => $tenant->first_name . ' ' . $tenant->last_name,
                'email' => $tenant->email,
                'password' => Hash::make($password),
            ]);

            // Assign the role 'locataire' to the user
            $user->assignRole('locataire');

            // Associate the user with the tenant
            $tenant->user_id = $user->id;
            $tenant->save();

            // Send the email with the account details
            Mail::to($tenant->email)->send(new NewTenantAccount($user, $password));

            // Return the created account details
            return response()->json([
                'message' => 'Compte créé avec succès.',
                'email' => $tenant->email,
                'password' => $password
            ]);
        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Erreur lors de la création du compte du locataire', [
                'tenant_id' => $id,
                'tenant_email' => $tenant->email ?? 'N/A',
                'exception_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Erreur lors de la création du compte.'], 500);
        }
    }
}
