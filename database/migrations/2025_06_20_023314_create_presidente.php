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
        Schema::create('presidente', function (Blueprint $table) {
            $table->id('pk_presidente');
            $table->string("nombre");
            $table->date("fec_inicio");
            $table->date("fec_fin");
            $table->text("descripcion");
            $table->string("img_presidente");
            $table->timestamps();
            $table->boolean("estatus")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presidente');
    }
};
