<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteEventoTest extends TestCase
{
  use RefreshDatabase;

    public function test_vista_listado_evento(): void
    {
        $response = $this->get('/lista-eventos');

        $response->assertStatus(200);
    }

    public function test_vista_infromacion_especifica_eventos(): void
    {
        $response = $this->get('/inf-evento');

        $response->assertStatus(302);
    }
}
