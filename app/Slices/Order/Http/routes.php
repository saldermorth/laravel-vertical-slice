<?php

use Illuminate\Support\Facades\Route;
use App\Slices\Order\Http\OrderController;

Route::post('/order', [OrderController::class, 'handle'])->name('order');