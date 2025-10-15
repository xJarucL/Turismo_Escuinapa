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
        Schema::create('comida_tipica', function (Blueprint $table) {
            $table->id('pk_comida_tipica');
            $table->string('nom_comida');
            $table->text('descripcion');
            $table->text('ingredientes');
            $table->string("img_comida");
            $table->unsignedBigInteger('fk_cat_comida');
            $table->foreign('fk_cat_comida')->references('pk_cat_comida')->on('categoria_comida');
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comida_tipica');
    }
};
