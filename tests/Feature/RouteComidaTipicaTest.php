<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteComidaTipicaTest extends TestCase
{
 use RefreshDatabase;

    public function test_vista_listado_comida_tipica(): void
    {
        $response = $this->get('/lista-comidas-tipicas');

        $response->assertStatus(200);
    }

    public function test_vista_infromacion_especifica_comida_tipica(): void
    {
        $response = $this->get('/inf-comida');

        $response->assertStatus(302);
    }
}
