<?php

namespace App\Slices\CreateOrder\Tests;

use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    public function test_can_handle_request()
    {
        $response = $this->post('/create-order', []);
        $response->assertStatus(200);
    }
}