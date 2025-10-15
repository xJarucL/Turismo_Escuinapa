<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Titulo_baner extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar un título y subtítulo en la tabla titulo_panel
        DB::table('titulo_panel')->insert([
            'titulo' => 'ESCUINAPA',
            'subtitulo' => 'Paraiso natural y cultural'
        ]); 
    }
}
