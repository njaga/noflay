<?php

use App\Http\Middleware\CheckSessionExpiration;
use App\Http\Middleware\CheckUserActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\IncreaseMemoryLimit;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\User;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            CheckUserStatus::class,
            CheckUserActivity::class,
            IncreaseMemoryLimit::class,

        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (!app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 401, 403, 419])) {
                return Inertia::render('Error', ['status' => $response->getStatusCode()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } elseif ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'La page a expiré, veuillez réessayer.',
                ]);
            }

            return $response;
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function () {
            $expiredUsers = User::where('role', 'admin_entreprise')
                                ->where('is_active', true)
                                ->where('demo_expiration', '<', now())
                                ->get();
            foreach ($expiredUsers as $user) {
                $user->is_active = false;
                $user->save();
            }
        })->daily();
    })

    ->create();





//Lorsque l'application sera en production

/**<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })

        ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                return Inertia::render('Error', ['status' => $response->getStatusCode()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } elseif ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'La page a expiré, veuillez réessayer.',
                ]);
            }

            return $response;
        });
    })->create();*/
