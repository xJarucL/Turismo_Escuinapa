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
        Schema::create('lugares_interes', function (Blueprint $table) {
            $table->id('pk_lugar_interes');
            $table->string("nombre");
            $table->text("descripcion");
            $table->text("direccion");
            $table->string('url_google_resena')->nullable();
            $table->string("img_portada");
            $table->timestamps();
            $table->boolean("estatus")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lugares_interes');
    }
};
