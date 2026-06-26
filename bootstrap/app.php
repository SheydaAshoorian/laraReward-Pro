<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\InsufficientPointsException; 
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

    $exceptions->render(function (InsufficientPointsException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        
    });


    $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
        return response()->json([
            'status' => 'forbidden',
            'message' => 'شما اجازه دسترسی به این منبع را ندارید.'
        ], 403);
    });

    })->create();


    
