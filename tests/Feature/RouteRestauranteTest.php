<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteRestauranteTest extends TestCase
{
    use RefreshDatabase;

    public function test_vista_listado_restaurante(): void
    {
        $response = $this->get('/lista-restaurante');

        $response->assertStatus(200);
    }

      public function test_vista_infromacion_especifica_restaurante(): void
    {
        $response = $this->get('/inf-restaurante');

        $response->assertStatus(302);
    }
}
