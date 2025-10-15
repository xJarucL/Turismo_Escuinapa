<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\LugaresInteres;

class MassLugaresInteresTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
    public function insertar_datos_masivos_en_lugares_interes()
    {   
        $cantidad = 1000;
        $datos = [];

        for($i= 0; $i<$cantidad; $i++){
            $datos[] = [
                'nombre' => "Lugar de Interés $i",
                'descripcion' => "Descripción del lugar $i",
                'direccion' => "Dirección ejemplo $i",
                'url_google_resena' => "https://maps.google.com/?q=lugar+$i",
                'img_portada' => "lugar_$i.jpg",
                'estatus' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $start = microtime(true);
        LugaresInteres::insert($datos);

        $end = microtime(true);
        $duracion = $end - $start;

        $this->assertDatabaseCount('lugares_interes', $cantidad);
        echo "\nTiempo de inserción de $cantidad registros: " . round($duracion, 2) . " segundos.\n";

    }
}
