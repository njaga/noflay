<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogUnauthorizedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if ($request->user() && !$request->user()->can($request->route()->getName())) {
            Log::warning('Unauthorized access attempt', [
                'user_id' => $request->user()->id,
                'route' => $request->route()->getName(),
                'ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
