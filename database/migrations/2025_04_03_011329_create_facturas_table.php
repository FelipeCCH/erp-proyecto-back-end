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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('id_factura');
            $table->unsignedBigInteger('id_comprador');
            $table->unsignedBigInteger('id_vendedor');
            $table->timestamp('fecha_emision')->useCurrent();
            $table->decimal('total', 10, 2);
            $table->timestamps();
        
            // Relaciones con Empresa para comprador y vendedor
            $table->foreign('id_comprador')
                  ->references('id_empresa')
                  ->on('empresas')
                  ->onDelete('cascade');
        
            $table->foreign('id_vendedor')
                  ->references('id_empresa')
                  ->on('empresas')
                  ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
