<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Hotel;

class ModelHotelTest extends TestCase
{
    
    public function test_prueba_modelo_hotel(): void
    {
        $hotel = new Hotel([
            'nom_hotel'=>'Saku',
            'img_hotel'=>'hotel_jpg',
            'direccion'=>'Norte perez',
            'contacto'=>'6666666',
            'descripcion'=>'Aqui muere gente',
            'link_hotel'=>'',
            'estatus'=>1,
        ]);

        $this->assertEquals('Saku', $hotel->nom_hotel);
        $this->assertEquals(1, $hotel->estatus);
    }
}
