<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Evento;

class ModelEverntoTest extends TestCase
{
  
    public function test_Prueba_modelo_evento(): void
    {
        $ev = new Evento([
            'nom_evento'=>'Nuevo sol',
            'img_promocional'=>'evento.jpg',
            'fecha_hora'=>'2025-01-01',
            'descripcion'=>'hola',
            'direccion'=>'pablo',
            'hora_evento'=>'08:00:00',
            'estatus'=>1,
        ]);

        $this->assertEquals('Nuevo sol', $ev->nom_evento);
        // $this->assertEquals(1, $ev->estatus);
    }
}
