<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_usuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles definidos para la tabla de tipo_usuarios
        DB::table('tipo_usuario')->insert([
            'nom_tipo_usuario' => 'Administrador',
            'estatus' => 1
        ]);
        DB::table('tipo_usuario')->insert([
            'nom_tipo_usuario' => 'Ayuntamiento',
            'estatus' => 1
        ]);
    }
}
