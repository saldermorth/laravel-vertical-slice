<?php

namespace App\Slices\Order\Tests;

use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_can_handle_request()
    {
        $response = $this->post('/order', []);
        $response->assertStatus(200);
    }
}