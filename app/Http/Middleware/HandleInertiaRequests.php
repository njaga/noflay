<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();
        $roles = $user ? $user->roles->toArray() : [];
        Log::info('Sharing user roles:', ['roles' => $roles]);

        return array_merge(parent::share($request), [
            'appName' => config('app.name'),
            'auth.role' => fn () => $request->user()
                ? $request->user()->getRoleNames()
                : null,
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'redirect_to_login' => fn () => $request->session()->get('redirect_to_login'),
            ],
        ]);
    }
}
