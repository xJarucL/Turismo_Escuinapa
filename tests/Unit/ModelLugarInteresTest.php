<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\LugaresInteres;

class ModelLugarInteresTest extends TestCase
{
  
    public function test_Prueba_de_modelo_lugar_interes()
    {
        $lugar = new LugaresInteres([
            'nombre' => 'Pino de locas',
            'descripcion' => 'El pino de locas se encuentra en tecualilla por el puente',
            'direccion' => 'Cerro 2',
            'url_google_resena' => '',
            'img_portada' => 'pino.jpg',
            'estatus' => 1,
        ]);

        $this->assertEquals('Pino de locas', $lugar->nombre);
        $this->assertEquals(1, $lugar->estatus);
    }
}
