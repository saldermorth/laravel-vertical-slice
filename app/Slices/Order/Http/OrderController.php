<?php

namespace App\Slices\Order\Http;

use App\Http\Controllers\Controller;
use App\Slices\Order\Actions\OrderHandler;
use App\Slices\Order\Http\OrderRequest;

class OrderController extends Controller
{
    public function handle(OrderRequest $request)
    {
        $handler = new OrderHandler();
        return $handler->handle($request->validated());
    }
}