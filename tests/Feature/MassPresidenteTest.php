<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Presidente;

class MassPresidenteTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]

    public function insertar_datos_masivos_en_presidente()
    {
        $cantidad = 1000;
        $datos = [];

        for($i = 0; $i<$cantidad; $i++){
            $datos[] = [
                'nombre' => "Presidente $i",
                'fec_inicio' => "1990-12-01",
                'fec_fin' => "2000-01-01",
                'descripcion' => "Descripcion de ejemplo $i",
                'img_presidente' =>"presi_$i.jpg",
                'estatus'=> 1,
            ];
        }
       $start = microtime(true);
    
        Presidente::insert($datos);
    

       $end = microtime(true);
       $duracion = $end-$start;

       $this->assertDatabaseCount('presidente', $cantidad);
       echo "Tiempo de inserción de $cantidad registros: " . round($duracion, 2) . " segundos.\n";
    }
}
