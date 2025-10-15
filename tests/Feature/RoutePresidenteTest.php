<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutePresidenteTest extends TestCase
{
    use RefreshDatabase;

    public function test_vista_listado_presidente(): void
    {
        $response = $this->get('/lista-presidentes');

        $response->assertStatus(200);
    }

    public function test_vista_infromacion_especifica_restaurante(): void
    {
        $response = $this->get('/inf-presidente');

        $response->assertStatus(302);
    }
}
