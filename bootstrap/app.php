<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $exceptions->render(function (\Throwable $e, $request) {

            if ($e instanceof ModelNotFoundException) {
                $model = class_basename($e->getModel());
                return response()->json([
                    'status' => 'Error',
                    'message' => $model . ' not found',
                ], 404);
            }
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Resource not found',
                ], 404);
            }

            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 500);

        });
    })->create();
