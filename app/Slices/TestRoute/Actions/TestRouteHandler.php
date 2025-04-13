<?php

namespace App\Slices\TestRoute\Actions;

use App\Slices\TestRoute\Models\TestRoute;

class TestRouteHandler
{
    public function handle(array $data)
    {
        // Create a new record
        $item = TestRoute::create($data);

        return response()->json([
            'success' => true,
            'id' => $item->id
        ]);
    }
}