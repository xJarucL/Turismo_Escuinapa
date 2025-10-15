<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Hotel;

class ControllerHotelTest extends TestCase
{
   
    public function test_Registro_datos_de_hotel()
    {
       $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');
        
        $datos=[
            'nom_hotel'=>'Saku',
            'img_hotel'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'direccion'=>'Norte perez',
            'contacto'=>'6666666',
            'descripcion'=>'Aqui muere gente',
            'link_hotel'=>'',
            'estatus'=>1,
        ];

        $response=$this->post(route('registrando_hotel'), $datos);

        $response->assertStatus(200);

    }

    public function test_validaciones_hotel_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
         $datos=[
            'nom_hotel'=>32432,
            'img_hotel'=> 'fotokslaf',
            'direccion'=>1232,
            'contacto'=>21321,
            'descripcion'=>'gdfgd',
            'link_hotel'=>'dsfsa_Fsdfkjas',
            'estatus'=>'boel',
        ];

        $response = $this->post(route('registrando_hotel'), $datos);

        $response->assertSessionHasErrors([
           'nom_hotel',
            'img_hotel',
            'direccion',
            'contacto',
            'descripcion',
            'link_hotel',
            'estatus',
        ]);
    }

    public function test_Actualizar_datos_hotel()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $hot=Hotel::create([
            'nom_hotel'=>'Saku',
            'img_hotel'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'direccion'=>'Norte perez',
            'contacto'=>'6666666',
            'descripcion'=>'Aqui muere gente',
            'link_hotel'=>'',
            'estatus'=>1,
        ]);

        $datos=[
            'pk_hotel'=>$hot->pk_hote,
            'nom_hotel'=>'Loser',
            'img_hotel'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'direccion'=>'Norte perez',
            'contacto'=>'6666666',
            'descripcion'=>'Aqui muere gente',
            'link_hotel'=>'',
            'estatus'=>1,
        ];

        $this->withSession(['hotel_seleccionado' =>$hot->pk_hote]); 
       
        $response = $this->post(route('editando_hotel'), array_merge($datos, [
            '_method' => 'PUT',
        ]));

        $response->assertStatus(302);

    }
}
