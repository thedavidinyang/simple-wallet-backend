<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->web(append: [
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

        ]);

        $middleware->api(prepend: [
            Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        $middleware->alias([
            'cors' => \App\Http\Middleware\Cors::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'jwt.verify' => \App\Http\Middleware\VerifyJWTToken::class,
            'is.staff' => \App\Http\Middleware\verifyStaff::class,
            'email.verify' => \App\Http\Middleware\verifyEmail::class,
            'phone.verify' => \App\Http\Middleware\verifyPhone::class,
            'verify.pin' => \App\Http\Middleware\VerifyPin::class,
            'json-response' =>  \App\Http\Middleware\EnsureJsonResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->context(fn() => [
            'user' => auth()->user() ? auth()->user()->only('id', 'uuid') : null,
            'url'  => request()->method() . ' ' . url()->full(),
        ]);

        $exceptions->dontTruncateRequestExceptions();

        // Ensure JSON responses for all exceptions
        $exceptions->shouldRenderJsonWhen(fn(Request $request, Throwable $e) => true);

        $exceptions->render(function (Throwable $exception) {
            $statusCode = $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException
                ? $exception->getStatusCode()
                : \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR;

            $response = [
                'message' => $exception->getMessage(),
                // 'code'    => $statusCode,
            ];

            if (app()->environment(['local', 'testing'])) {
                // $response['trace'] = $exception->getTrace();
            }

            return response()->json($response, $statusCode);
        });
    })->create();


    $app->register(App\Providers\ExceptionServiceProvider::class);

    return $app;
