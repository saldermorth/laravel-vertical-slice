<?php

use Illuminate\Support\Facades\Route;
use App\Slices\CreateOrder\Http\CreateOrderController;

Route::post('/create-order', [CreateOrderController::class, 'handle'])->name('create-order');