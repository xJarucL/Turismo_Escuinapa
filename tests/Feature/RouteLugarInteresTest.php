<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteLugarInteresTest extends TestCase
{
    use RefreshDatabase;

    public function test_vista_listado_lugar_interes(): void
    {
        $response = $this->get('/lista_lugares');

        $response->assertStatus(404);
    }

    public function test_vista_infromacion_especifica_lugar_interes(): void
    {
        $response = $this->get('/inf-lugar');

        $response->assertStatus(302);
    }
}
