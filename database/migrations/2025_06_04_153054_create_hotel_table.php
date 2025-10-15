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
        Schema::create('hotel', function (Blueprint $table) {
            $table->id('pk_hotel');
            $table->string("nom_hotel");
            $table->string("img_hotel");
            $table->string("direccion");
            $table->string("contacto");
            $table->text("descripcion");
            $table->text('link_hotel')->nullable();
            $table->timestamps();
            $table->boolean("estatus")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel');
    }
};
