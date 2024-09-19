<?php

use App\Exceptions\AppException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AppException $e, Request $request) {
            $response = [
                'message' => $e->getMessage(),
            ];

            if (!is_null($e) && config('app.debug')){
                $response['debug'] = [
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString()
                ];
            }

            if ($request->is('api/*')) {
                return response()->json($response, $e->getCode());
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], Response::HTTP_UNAUTHORIZED);
            }
        });

        /* $exceptions->render(function (\Throwable $e, Request $request) {
            $response = [
                'message' => 'Something went wrong'
            ];

            if (!is_null($e) && config('app.debug')){
                $response['debug'] = [
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString()
                ];
            }

            if ($request->is('api/*')) {
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }); */

        $exceptions->render(function (\Illuminate\Validation\ValidationException $throwable) {
            Log::info($throwable->errors());
        });

    })->create();
