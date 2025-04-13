<?php

namespace App\Slices\CreateOrder\Http;

use App\Http\Controllers\Controller;
use App\Slices\CreateOrder\Actions\CreateOrderHandler;
use App\Slices\CreateOrder\Http\CreateOrderRequest;

class CreateOrderController extends Controller
{
    public function handle(CreateOrderRequest $request)
    {
        $handler = new CreateOrderHandler();
        return $handler->handle($request->validated());
    }
}