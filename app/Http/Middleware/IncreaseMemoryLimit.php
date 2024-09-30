<?php

namespace App\Http\Middleware;

use Closure;

class IncreaseMemoryLimit
{
    public function handle($request, Closure $next)
    {
        ini_set('memory_limit', '256M');
        return $next($request);
    }
}
