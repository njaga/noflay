<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (!$user->isActive() || ($user->company && !$user->company->isActive()))) {
            Auth::guard('web')->logout();
            return Inertia::render('InactiveUser')->toResponse($request);
        }

        return $next($request);
    }
}
