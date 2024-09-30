<?php

namespace App\Http\Controllers;

use App\Mail\HelpEmail;
use App\Notifications\CallbackRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Mail\CallbackRequestedMail;

class HelpController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Mail::to('contact@ndiagandiaye.com')->send(new HelpEmail($request->only(['name', 'email', 'message'])));

        return back()->with('success', 'Votre message a été envoyé avec succès.');
    }

    public function requestCallback(Request $request)
    {
        Log::info('Demande de rappel reçue', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'preferredTime' => 'required|string|in:morning,afternoon,evening',
        ]);

        try {
            // Notifier l'utilisateur connecté
            $request->user()->notify(new CallbackRequested($request->only(['name', 'phone', 'preferredTime'])));
            Log::info('Notification envoyée à l\'utilisateur connecté');

            // Notifier l'administrateur
            $admin = User::firstWhere('email', 'contact@ndiagandiaye.com');
            if ($admin) {
                $admin->notify(new CallbackRequested($request->only(['name', 'phone', 'preferredTime'])));
                Log::info('Notification envoyée à l\'administrateur');
            } else {
                Log::warning('Administrateur non trouvé pour l\'email: contact@ndiagandiaye.com');
            }

            // Envoyer un e-mail directement à l'adresse spécifiée
            Mail::to('contact@ndiagandiaye.com')->send(new CallbackRequestedMail($request->only(['name', 'phone', 'preferredTime'])));
            Log::info('E-mail envoyé directement à contact@ndiagandiaye.com');

            return back()->with('success', 'Votre demande de rappel a été enregistrée.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification de rappel', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande. Veuillez réessayer.');
        }
    }
}
