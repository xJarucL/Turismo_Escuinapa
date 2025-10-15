<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Restaurante;

class ControllerRestauranteTest extends TestCase
{
   
    public function test_Registro_dato_restaurante()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $datos=[
            'nom_restaurante'=>'Nombre de ejemplo',
            'direccion'=>'Calle buena vista',
            'hora_apertura'=>'08:00:00',
            'hora_cierre'=>'20:00:00',
            'descripcion'=>'Sopla las velas',
            'img_promocional'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'tel_restaurante' => "695118872",
            'email_restaurante'=>'correo@corre.com',
            'url_google_reseña'=>'',
            'estatus'=>1,
        ];

        $response=$this->post(route('registrando_restaurante'), $datos);

        $response->assertStatus(302);

    }
    public function test_validaciones_restaurante_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
        $datos = [
            'nom_restaurante' => 2314, 
            'direccion' =>12324, 
            'hora_apertura' => 'horea', 
            'hora_cierre' => 'final',   
            'descripcion' => 213, 
            'img_promocional' => 'imagen',
            'tel_restaurante' => 32423,
            'email_restaurante' => 'sibnsidofds', 
            'url_google_reseña' => 'dsa_fasdklfj_dfsa', 
            'estatus' => 'vvdafs', 
        ];


        $response = $this->post(route('registrando_restaurante'), $datos);

        $response->assertSessionHasErrors([
            'nom_restaurante',
            'direccion',
            'hora_apertura',
            'hora_cierre',
            'descripcion',
            'img_promocional',
            'tel_restaurante',
            'email_restaurante',
            'url_google_reseña',
            'estatus',
        ]);
    }

    public function test_actualizar_presidente()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $rest = new Restaurante();
        
        $rest->forceFill([
            'nom_restaurante'=>'Nombre de ejemplo',
            'direccion'=>'Calle buena vista',
            'hora_apertura'=>'08:00:00',
            'hora_cierre'=>'20:00:00',
            'descripcion'=>'Sopla las velas',
            'img_promocional'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'tel_restaurante' => "695118872",
            'email_restaurante'=>'correo@corre.com',
            'url_google_reseña'=>'',
            'estatus'=>1,
        ])->save();

        $datos=[
            'pk_restaurante'=>$rest->pk_restaurante,
            'nom_restaurante'=>'Pilla Pilla',
            'direccion'=>'Calle buena vista',
            'hora_apertura'=>'08:00:00',
            'hora_cierre'=>'20:00:00',
            'descripcion'=>'Sopla las velas',
            'img_promocional'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'tel_restaurante' => "695118872",
            'email_restaurante'=>'correo@corre.com',
            'url_google_reseña'=>'',
            'estatus'=>1,
        ];

        $this->withSession(['restaurante_seleccionado' =>$rest->pk_restaurante]);
        
        $response = $this->post(route('editando_restaurante'), array_merge($datos, [
            '_method' => 'PUT',
        ]));

        $response->assertStatus(302);

    }
}
