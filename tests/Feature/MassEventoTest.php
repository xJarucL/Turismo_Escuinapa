<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Evento;

class MassEventoTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
   
    public function insertar_datos_masivo_en_eventos(): void
    {
        $cantidad = 1000;
        $datos = [];

        for($i = 0; $i<$cantidad; $i++){
            $datos[]=[
                'nom_evento'=> "Evento $i",
                'img_promocional'=> "evento_$i.jpg",
                'fecha_hora'=>"2025-12-24",
                'descripcion'=> "Descripción del evento número $i",
                'direccion'=> "Dirección del evento $i",
                'hora_evento'=> "12:00:00",
                'estatus'=>1,
            ];
        }
         
        $start = microtime(true);
    
        Evento::insert($datos);
    

       $end = microtime(true);
       $duracion = $end-$start;

       $this->assertDatabaseCount('evento', $cantidad);
       echo "\nPrueba de inserccion en la base de datos con la cantidad de $cantidad resgitros.\n";
    }
}
