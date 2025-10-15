<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteHotelTest extends TestCase
{
   use RefreshDatabase;

    public function test_vista_listado_hotel(): void
    {
        $response = $this->get('/lista-hoteles');

        $response->assertStatus(200);
    }

    public function test_vista_infromacion_especifica_hotel(): void
    {
        $response = $this->get('/inf-hotel');

        $response->assertStatus(302);
    }
}
