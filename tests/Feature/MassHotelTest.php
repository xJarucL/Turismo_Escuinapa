<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Hotel;


class MassHotelTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]

    public function insertar_datos_masivos_en_hotel()
    {
       $cantidad = 1000;
       $datos = [];

       for($i=0; $i<$cantidad; $i++){
            $datos[]=[
                'nom_hotel' => "Hotel $i",
                'img_hotel' => "hotel_$i.jpg",
                'direccion' => "Direccion de ejemplo $i",
                'contacto' => "666$i",
                'descripcion' => "Direccion de ejemplo $i",
                'link_hotel'=> "",
                'estatus' => 1
            ];
          
       }
       $start = microtime(true);
    
        Hotel::insert($datos);
    

       $end = microtime(true);
       $duracion = $end-$start;

       $this->assertDatabaseCount('hotel', $cantidad);
       echo "\nPrueba de inserccion en la base de datos con la cantidad de $cantidad resgitros.\n";
    }
}
