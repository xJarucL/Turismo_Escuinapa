<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurante;

class MassRestauranteTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function insertar_datos_masivos_en_restaurantes()
    {
        // \DB::setDefaultConnection('mysql');
        // dd(config('database.default'), config('database.connections.mysql.database'));

       $cantidad = 20000;
       $datos = [];

       for($i = 0; $i<$cantidad; $i++){
            $datos[] = [
                'nom_restaurante' => "Restaurante $i",
                'direccion' => "Direccion de ejemplo$i",
                'hora_apertura' => "11:20:00",
                'hora_cierre' => "20:00:00",
                'descripcion' => "Descripcion de ejemplo $i",
                'img_promocional' => "img_restaurante$i.jpg",
                'tel_restaurante' => "695118872$i",
                'email_restaurante' => "correo$i@correo.com",
                'url_google_reseña' => "",
                'estatus' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
       }

       $chunkSize = 500;

       $start = microtime(true);
       foreach(array_chunk($datos, $chunkSize) as $lote){
            Restaurante::insert($lote);
       }

       $end = microtime(true);
       $duracion = $end - $start;

       $this->assertDatabaseCount('restaurante', $cantidad);
       echo "\nTiempo de inserción de $cantidad registros (en bloques de $chunkSize): " . round($duracion, 2) . " segundos.\n";
    }
}
