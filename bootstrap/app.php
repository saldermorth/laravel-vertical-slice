<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Use direct path instead of app_path helper
$basePath = dirname(__DIR__);
$slicesDir = $basePath . '/app/Slices';
$providers = [];

// Use native PHP functions instead of facades
if (is_dir($slicesDir)) {
    // Get all directories in the slices folder
    $slices = array_filter(glob($slicesDir . '/*'), 'is_dir');

    foreach ($slices as $slice) {
        $sliceName = basename($slice);
        $providerPath = "{$slice}/Providers/{$sliceName}ServiceProvider.php";

        if (file_exists($providerPath)) {
            $providers[] = "App\\Slices\\{$sliceName}\\Providers\\{$sliceName}ServiceProvider";
        }
    }
}

return Application::configure(basePath: $basePath)
    ->withProviders($providers)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Your middleware configuration
    })
    ->withCommands([
        \App\Console\Commands\MakeSliceCommand::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
