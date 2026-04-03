<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_application_responds(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
