<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadsWebhookTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function intercome()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

    }
}
