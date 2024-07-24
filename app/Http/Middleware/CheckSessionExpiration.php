<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CheckSessionExpiration
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($this->isSessionExpired($request)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Store a flag and expiration time in the session
                $expirationTime = now()->addHour();
                $request->session()->put('redirect_to_login_at', $expirationTime);

                return Inertia::render('Error', [
                    'status' => 401,
                    'message' => 'Votre session a expiré. Vous serez redirigé vers la page de connexion dans 1 heure.',
                    'expirationTime' => $expirationTime->timestamp
                ])->toResponse($request);
            }
        }
        return $next($request);
    }

    private function isSessionExpired(Request $request)
    {
        $lastActivity = $request->session()->get('last_activity');
        $sessionLifetime = config('session.lifetime') * 60; // en secondes
        if (!$lastActivity) {
            return false;
        }
        return (time() - $lastActivity) > $sessionLifetime;
    }
}
