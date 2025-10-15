<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ComidaTipica;
use App\Models\CategoriaComida;

class MassComidaTipicaTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]

    public function insertar_datos_masivos_en_comida_tipica()
    {
        $cat = CategoriaComida::forceCreate([
            'nom_cat' => 'Ejemplo categoria',
            'estatus' => 1,
        ]);

        $cantidad = 1000;
        $datos = [];

        for($i = 0; $i<$cantidad; $i++){
            $datos[]=[
                'nom_comida'=>"Comida típica $i",
                'descripcion'=>"Descripcion de ejemplo $i",
                'ingredientes'=> "Ingrediente de ejemplo $i",
                'img_comida' => "comida_$i.jpg",
                'fk_cat_comida'=>$cat->pk_cat_comida,
                'estatus'=>1,
            ];
        }

        $start = microtime(true);
    
        ComidaTipica::insert($datos);
    

       $end = microtime(true);
       $duracion = $end-$start;

       $this->assertDatabaseCount('comida_tipica', $cantidad);
       echo "\nPrueba de inserccion en la base de datos con la cantidad de $cantidad resgitros.\n";
    }
}
