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
        Schema::create('restaurante', function (Blueprint $table) {
            $table->id("pk_restaurante");
            $table->string("nom_restaurante");
            $table->text("direccion");
            $table->time("hora_apertura");
            $table->time("hora_cierre");
            $table->text("descripcion");
            $table->string("img_promocional");
            $table->string("tel_restaurante");
            $table->string("email_restaurante");
            $table->text("url_google_reseña")->nullable();
            $table->timestamps();
            $table->boolean("estatus")->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurante');
    }
};
