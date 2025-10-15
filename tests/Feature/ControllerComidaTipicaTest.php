<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\ComidaTipica;
use App\Models\CategoriaComida;



class ControllerComidaTipicaTest extends TestCase
{
    
    public function test_Registro_dato_comida_tipica()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $cat = CategoriaComida::forceCreate([
            'nom_cat' => 'Ejemplo categoria',
            'estatus' => 1,
        ]);

        $datos=[
            'nom_comida'=>"Comida típica",
            'descripcion'=>"Descripcion de ejemplo ",
            'ingredientes'=> "Ingrediente de ejemplo ",
            'img_comida' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ), 
            'fk_cat_comida'=>$cat->pk_cat_comida,
            'estatus'=>1,
        ];

        $response=$this->post(route('registrando-comida'), $datos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('comida_tipica', ['nom_comida' => 'Comida tipica',]);
    }

    public function test_validaciones_comida_con_datos_invalidos()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        
         $datos=[
          'nom_comida'=>345,
            'descripcion'=>324,
            'ingredientes'=> 4324,
            'img_comida' =>'sfddsiosd' , 
            'fk_cat_comida'=>'dfs',
            'estatus'=>'ds',
        ];

        $response = $this->post(route('registrando-comida'), $datos);

        $response->assertSessionHasErrors([
       'nom_comida',
            'descripcion',
            'ingredientes',
            'img_comida', 
            'fk_cat_comida',
            'estatus',
        ]);
    }

    public function test_Actualizar_datos_comida_tipica()
    {
        $this->withoutMiddleware();

        $rutaImagen = public_path('storage/lugar_img/la-cruz-mas-botina/wA8wTMNw9djAN3SfUKi7xdcjJWyCrBf2zHPPGrRm.png');

        $cat = CategoriaComida::forceCreate([
            'nom_cat' => 'Ejemplo categoria',
            'estatus' => 1,
        ]);

        $comi=ComidaTipica::create([
            'nom_comida'=>"Comida típica",
            'descripcion'=>"Descripcion de ejemplo ",
            'ingredientes'=> "Ingrediente de ejemplo ",
            'img_comida' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ), 
            'fk_cat_comida'=>$cat->pk_cat_comida,
            'estatus'=>1,
        ]);

        $datos=[
            'pk_comida_tipica'=>$comi->pk_comida_tipica,
            'nom_comida'=>"Comida sabrosa",
            'descripcion'=>"Descripcion de ejemplo ",
            'ingredientes'=> "Ingrediente de ejemplo ",
            'img_comida' => new UploadedFile(
                $rutaImagen,
                'pino.png',
                'image/png',
                null,
                true
            ), 
            'fk_cat_comida'=>$cat->pk_cat_comida,
            'estatus'=>1,
        ];

        $this->withSession(['comida_seleccionada'=>$comi->pk_comida_tipica]);

        $response=$this->post(route('editando_comida_tipica'), array_merge($datos, [
            '_method' =>'PUT',
        ]));

        $response->assertStatus(302);
        $this->assertDatabaseHas('comida_tipica', ['nom_comida'=>"Comida sabrosa"]);
    }
}
