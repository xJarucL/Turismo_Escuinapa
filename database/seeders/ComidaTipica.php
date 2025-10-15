<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ComidaTipica extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categoria_comida')->insert([
            'nom_cat' => 'Bebidas',
            'estatus' => 1
        ]);
        DB::table('categoria_comida')->insert([
            'nom_cat' => 'Mariscos',
            'estatus' => 1
        ]);
        DB::table('categoria_comida')->insert([
            'nom_cat' => 'Antojos',
            'estatus' => 1
        ]);
        DB::table('categoria_comida')->insert([
            'nom_cat' => 'Tamales',
            'estatus' => 1
        ]);
        DB::table('categoria_comida')->insert([
            'nom_cat' => 'Platillos Tradicionales',
            'estatus' => 1
        ]);
    }
}
