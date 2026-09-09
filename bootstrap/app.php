<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mengarahkan tamu (guest) ke halaman login admin
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Jaring penangkap error 419 Super Ketat
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Tangkap jika error adalah TokenMismatch ATAU HttpException dengan status 419
            if ($e instanceof \Illuminate\Session\TokenMismatchException || ($e instanceof HttpException && $e->getStatusCode() === 419)) {
                return redirect()->route('admin.login')
                    ->withInput()
                    ->with('error', 'Sesi halaman Anda telah berakhir karena terlalu lama diam. Silakan coba login kembali.');
            }
        });

    })->create();