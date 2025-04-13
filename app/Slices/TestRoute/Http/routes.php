<?php

use Illuminate\Support\Facades\Route;
use App\Slices\TestRoute\Http\TestRouteController;

// Add this GET route for testing
Route::get('/test-route', function () {
    return 'Test route is working!';
});

// Keep the original POST route
Route::post('/test-route', [TestRouteController::class, 'handle'])->name('test-route');
