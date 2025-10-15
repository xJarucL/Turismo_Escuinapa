<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Presidente;

class ControllerPresidenteTest extends TestCase
{
  
    public function test_Registro_dato_presidente()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $datos=[
            'nombre' =>'Juant',
            'fec_inicio'=>'1999-01-01',
            'fec_fin'=>'2005-01-01',
            'descripcion'=>'era grosero',
            'img_presidente'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus'=>1,
        ];

        $response=$this->post(route('registrando_presidente'), $datos);

        $response->assertStatus(200);

    }

    public function test_validaciones_presidentes_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
       $datos=[
            'nombre' =>4554,
            'fec_inicio'=>'fehca',
            'fec_fin'=>'fin',
            'descripcion'=>123,
            'img_presidente'=> 'll',
            'estatus'=>'',
        ];

        $response = $this->post(route('registrando_presidente'), $datos);

        $response->assertSessionHasErrors([
            'nombre',
            'fec_inicio',
            'fec_fin',
            'descripcion',
            'img_presidente',
            'estatus',
        ]);
    }

    public function test_Actualizar_datos_presidente()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $presi=Presidente::create([
            'nombre' =>'Juant',
            'fec_inicio'=>'1999-01-01',
            'fec_fin'=>'2005-01-01',
            'descripcion'=>'era grosero',
            'img_presidente'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus'=>1,
        ]);

           $datos=[
            'pk_presidente'=>$presi->pk_presidente,
            'nombre' =>'Daniel',
            'fec_inicio'=>'1999-01-01',
            'fec_fin'=>'2005-01-01',
            'descripcion'=>'era grosero',
            'img_presidente'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus'=>1,
        ];

        $this->withSession(['presidente_seleccionado'=>$presi->pk_presidente]);

        $response = $this->post(route('editando_presidente'), array_merge($datos, [
            '_method' => 'PUT',
        ]));

        $response->assertStatus(302);

    }
}
