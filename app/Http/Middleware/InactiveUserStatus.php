<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InactiveUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_active) {
            return redirect()->route('inactive.user'); // Rediriger vers une route nommée 'inactive.user'
        }

        return $next($request);
    }
}
