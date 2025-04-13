<?php

namespace App\Slices\TestRoute\Tests;

use Tests\TestCase;

class TestRouteTest extends TestCase
{
    public function test_can_handle_request()
    {
        $response = $this->post('/test-route', []);
        $response->assertStatus(200);
    }
}