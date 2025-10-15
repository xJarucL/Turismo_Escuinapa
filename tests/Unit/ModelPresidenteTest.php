<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Presidente;

class ModelPresidenteTest extends TestCase
{
   
    public function test_Prueba_modelo_presidente()
    {
        $presi = new Presidente([
            'nombre' =>'Juant',
            'fec_inicio'=>'1999-01-01',
            'fec_fin'=>'2005-01-01',
            'descripcion'=>'era grosero',
            'img_presidente'=>'presi.jpg',
            'estatus'=>1,
        ]);

        $this->assertEquals('Juant', $presi->nombre);
        $this->assertEquals(1, $presi->estatus);
    }
}
