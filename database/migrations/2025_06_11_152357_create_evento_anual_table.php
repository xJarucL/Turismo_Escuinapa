<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evento_anual', function (Blueprint $table) {
            $table->id('pk_evento_anual');
            $table->string("nom_evento");
            $table->string("img_promocional");
            $table->enum('tipo_fecha', ['fija', 'variable', 'indefinida']);
            $table->string('fecha_referencia'); // Ejemplo: '05-10', '3-domingo-junio', etc.
            $table->Text("descripcion");
            $table->Text("direccion");
            $table->time("hora_evento");
            $table->timestamps();
            $table->boolean("estatus")->default(true); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_anual');
    }
};
