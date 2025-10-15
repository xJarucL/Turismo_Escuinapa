<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(Tipo_usuarios::class);
        $this->call(ComidaTipica::class);
        $this->call(UserSeeder::class);
        $this->call(Titulo_baner::class);
    }
}
