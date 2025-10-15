<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Restaurante;

class ModelRestauranteTest extends TestCase
{
    public function test_Prueba_modelo_restaurante(): void
    {
        $rest = new Restaurante([
            'nom_restaurante'=>'Nombre de ejemplo',
            'direccion'=>'Calle buena vista',
            'hora_apertura'=>'08:00:00',
            'hora_cierre'=>'20:00:00',
            'descripcion'=>'Sopla las velas',
            'img_promocional'=>'Rest.jpg',
            'email_restaurante'=>'correo@corre.com',
            'url_google_reseña'=>'',
            'estatus'=>1,
        ]);

        $this->assertEquals('Nombre de ejemplo', $rest->nom_restaurante);
        $this->assertEquals(1, $rest->estatus);
    }
}
