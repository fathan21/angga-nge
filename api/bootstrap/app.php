<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->name('app')
                ->group(base_path('routes/api_admin.php'));

            Route::middleware('api')
                ->name('open')
                ->group(base_path('routes/api_open.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->redirectGuestsTo(function() {
            abort(401,'not auth');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            // if ($request->is('api/*')) {
            //     return true;
            // }
            return true;
            // return $request->expectsJson();
        });
        // $exceptions->respond(function (Response $response) {
        //     dd($response->getMessage());
        //     $result = [
        //         'status' => 'error',
        //         'code' => $response->getStatusCode(),
        //         // 'message' => $response->getMessage()
        //     ];
        //     dd($result);
        //     $r =  response()->json($result,$this->getStatusCode());
        //     dd($r);
        // });
        $exceptions->render(function (HttpException $exception, Request $request) {
            $result = [
                'status' => 'error',
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];
            return response()->json($result,$exception->getStatusCode());
        });

        $exceptions->render(function (ValidationException $exception, Request $request) {
            $result = [
                'status' => 'error',
                'code' => 422,
                'message' => $exception->getMessage()
            ];
            return response()->json($result,422);
        });
        $exceptions->render(function (QueryException $exception, Request $request) {
            $result = [
                'status' => 'error',
                'code' => 500,
                'message' => "SQL ERROR: ".$exception->getMessage()
            ];
            return response()->json($result,422);
        });
        $exceptions->render(function (RouteNotFoundException $exception, Request $request) {
            $result = [
                'status' => 'error',
                'code' => 404,
                'message' => "page not found"
            ];
            return response()->json($result,422);
        });

    })->create();
