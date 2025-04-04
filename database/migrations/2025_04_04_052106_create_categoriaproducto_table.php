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
        Schema::create('categoria_producto', function (Blueprint $table) {
            $table->id('id_categoriaProducto');
            $table->unsignedBigInteger('id_producto');
            $table->string('imagen', 255);
            $table->foreign('id_producto')
                  ->references('id_producto')
                  ->on('producto')
                  ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoriaproducto');
    }
};
