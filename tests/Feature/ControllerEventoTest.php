<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;

use Tests\TestCase;
use App\Models\Evento;

class ControllerEventoTest extends TestCase
{
 
    public function test_Registrar_dato_de_evento(): void
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $datos=[
            'nom_evento'=>'Nuevo sol',
            'img_promocional'=> new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ),
            'fecha_hora'=>'2025-01-01',
            'descripcion'=>'hola',
            'direccion'=>'pablo',
            'hora_evento'=>'08:00:00',
            'estatus'=>1,
        ];

        $response=$this->post(route('registrando_evento'), $datos);

        $response->assertStatus(302);

        // $this->assertDatabaseHas('evento', ['nom_evento' => 'Nuevo sol']);

        
    }

    public function test_validaciones_evento_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
         $datos=[
           'nom_evento'=>12324,
            'img_promocional'=>  'noasd',
            'fecha_hora'=>' asdfasd',
            'descripcion'=>'',
            'direccion'=>12123,
            'hora_evento'=>'v',
            'estatus'=>1,
        ];

        $response = $this->post(route('registrando_evento'), $datos);

        $response->assertSessionHasErrors([
        'nom_evento',
            'img_promocional',
            'fecha_hora',
            'descripcion',
            'direccion',
            'hora_evento',
            'estatus',
        ]);
    }

    public function test_Actualizar_datos_de_evento()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $ev = new Evento();

        $ev->forceFill([
            'nom_evento'=>'Nuevo sol',
            'img_promocional'=> new UploadedFile($rutaImagen, 'pino.png', 'image/png', null, true),
            'fecha_hora'=>'2025-01-01',
            'hora_evento'=>'08:00:00',
            'descripcion'=>'hola',
            'direccion'=>'pablo',
            'estatus'=>1,
        ])->save();

        $datos = [
            'pk_evento'=>$ev->pk_evento,
            'nom_evento'=>'Nuevo',
            'img_promocional'=> new UploadedFile($rutaImagen, 'pino.png', 'image/png', null, true),
            'fecha_hora'=>'2025-01-01',
            'hora_evento'=>'08:00:00',
            'descripcion'=>'hola',
            'direccion'=>'pablo',
            'estatus'=>1,
        ];



        $this->withSession(['evento_seleccionado' => $ev->pk_evento,]);

        $response = $this->post(route('editando_evento'), array_merge($datos, [
            '_method' => 'PUT',
        ]));

        $response->assertStatus(302);

    }
}
