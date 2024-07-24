<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');
            $currentTime = Carbon::now();

            if ($lastActivity && $currentTime->diffInSeconds($lastActivity) >= 3600) {
                Auth::logout();
                session()->flush();
                session(['session_expired' => true]);
                return redirect()->route('expired-session');
            }

            session(['lastActivityTime' => $currentTime]);
        }

        return $next($request);
    }
}
