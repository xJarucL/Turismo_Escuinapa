<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\LugaresInteres;

class ControllerLugarInteresTest extends TestCase
{
   
    public function test_Registro_datos_lugar_interes()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $datos=[
            'nombre' => 'Pino de locas',
            'descripcion' => 'El pino de locas se encuentra en tecualilla por el puente',
            'direccion' => 'Cerro 2',
            'url_google_resena' => '',
            'img_portada' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus' => 1,
        ];

        $response=$this->post(route('registrando_lugar_interes'), $datos);
        // $response->dump();
        $response->assertStatus(200);
        $this->assertDatabaseHas('lugares_interes', ['nombre' => 'Pino de locas',]);
    }

    public function test_validaciones_lugar_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
         $datos=[
            'nombre' => 121,
            'descripcion' => '',
            'direccion' => 12321,
            'url_google_resena' => 'sdfsdklj df',
            'img_portada' => 'ssdasd',
            'estatus' => 'ddddd',
        ];

        $response = $this->post(route('registrando_lugar_interes'), $datos);

        $response->assertSessionHasErrors([
            'nombre',
            'descripcion',
            'direccion',
            'url_google_resena',
            'img_portada',
            'estatus',
        ]);
    }

    public function test_actualizar_lugar_interes()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $lugar=LugaresInteres::create([
            'nombre' => 'Pino de locas',
            'descripcion' => 'El pino de locas se encuentra en tecualilla por el puente',
            'direccion' => 'Cerro 2',
            'url_google_resena' => '',
            'img_portada' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus' => 1,
        ]);

        $datos=[
            'pk_lugar_interes' => $lugar->pk_lugar_interes,
            'nombre' => 'Pino de fuego',
            'descripcion' => 'El pino de locas se encuentra en tecualilla por el puente',
            'direccion' => 'Cerro 2',
            'url_google_resena' => '',
            'img_portada' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'estatus' => 1,
        ];

        $this->withSession(['lugar_seleccionado' => $lugar->pk_lugar_interes]);

        $response = $this->post(route('editando_lugar'), array_merge($datos, [
            '_method' => 'PUT',
        ]));
        $response->assertStatus(302);
        $this->assertDatabaseHas('lugares_interes', ['nombre' => 'Pino de fuego']);

    }
}
