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
        Schema::create('imagenes_restaurantes', function (Blueprint $table) {
            $table->id("pk_img_restaurante");
            $table->foreignId('pk_restaurante')->constrained('restaurante', 'pk_restaurante')->onDelete('cascade');
            $table->string('ruta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes_restaurantes');
    }
};
