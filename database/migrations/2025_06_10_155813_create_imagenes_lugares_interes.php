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
        Schema::create('imagenes_lugares_interes', function (Blueprint $table) {
            $table->id('pk_img_lugar_interes');
            $table->foreignId('pk_lugar_interes')->constrained('lugares_interes', 'pk_lugar_interes')->onDelete('cascade');
            $table->string('ruta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes_lugares_interes');
    }
};
