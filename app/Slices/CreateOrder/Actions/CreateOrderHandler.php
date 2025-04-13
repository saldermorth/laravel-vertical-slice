<?php

namespace App\Slices\CreateOrder\Actions;

class CreateOrderHandler
{
    public function handle(array $data)
    {
        // Business logic here
        return response()->json(['success' => true]);
    }
}