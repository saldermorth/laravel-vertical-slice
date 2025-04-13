<?php

namespace App\Slices\Order\Actions;

use App\Slices\Order\Models\Order;

class OrderHandler
{
    public function handle(array $data)
    {
        // Create a new record
        $item = Order::create($data);

        return response()->json([
            'success' => true,
            'id' => $item->id
        ]);
    }
}