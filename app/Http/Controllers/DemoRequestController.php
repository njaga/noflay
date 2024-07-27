<?php

namespace App\Http\Controllers;

use App\Mail\DemoAccountDetailsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoRequestMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
        ]);

        $demoRequestData = $request->only(['name', 'email', 'phone', 'company']);

        try {
            DB::beginTransaction();

            // Vérifier si l'utilisateur existe déjà
            $existingUser = User::where('email', $demoRequestData['email'])->first();
            if ($existingUser) {
                DB::rollBack();
                return response()->json(['error' => 'user_exists', 'message' => 'Un compte avec cet email existe déjà.'], 409);
            }

            // Vérifier si l'entreprise existe déjà
            $existingCompany = Company::where('email', $demoRequestData['email'])->first();
            if ($existingCompany) {
                $entreprise = $existingCompany;
            } else {
                $entreprise = Company::create([
                    'name' => $demoRequestData['company'],
                    'email' => $demoRequestData['email'],
                    'phone' => $demoRequestData['phone'],
                ]);
            }

            // Créer l'utilisateur admin_entreprise
            $password = Str::random(12);
            $user = User::create([
                'name' => $demoRequestData['name'],
                'email' => $demoRequestData['email'],
                'phone' => $demoRequestData['phone'],
                'password' => bcrypt($password),
                'role' => 'admin_entreprise',
                'entreprise_id' => $entreprise->id,
                'is_active' => true,
                'demo_expiration' => Carbon::now()->addDays(15),
            ]);

            $user->assignRole('admin_entreprise');

            // Envoyer les emails
            Mail::to('contact@ndiagandiaye.com')->send(new DemoRequestMail($demoRequestData));
            Mail::to($demoRequestData['email'])->send(new DemoAccountDetailsMail($demoRequestData, $password));

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Votre demande de démo a été envoyée avec succès !']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Demo request failed: ' . $e->getMessage());
            return response()->json(['error' => 'server_error', 'message' => 'Une erreur est survenue lors du traitement de votre demande.'], 500);
        }
    }
}
