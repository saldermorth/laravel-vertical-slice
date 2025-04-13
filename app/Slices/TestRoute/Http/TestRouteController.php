<?php

namespace App\Slices\TestRoute\Http;

use App\Http\Controllers\Controller;
use App\Slices\TestRoute\Actions\TestRouteHandler;
use App\Slices\TestRoute\Http\TestRouteRequest;

class TestRouteController extends Controller
{
    public function handle(TestRouteRequest $request)
    {
        $handler = new TestRouteHandler();
        return $handler->handle($request->validated());
    }
}