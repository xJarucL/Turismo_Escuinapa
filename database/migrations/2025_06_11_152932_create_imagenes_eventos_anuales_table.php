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
        Schema::create('imagenes_eventos_anuales', function (Blueprint $table) {
            $table->id('pk_img_evento_anual');
            $table->foreignId('pk_evento_anual')->constrained('evento_anual', 'pk_evento_anual')->onDelete('cascade');
            $table->string('ruta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes_eventos_anuales');
    }
};
