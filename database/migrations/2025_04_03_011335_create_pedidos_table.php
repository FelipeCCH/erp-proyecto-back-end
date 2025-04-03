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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_factura');
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->enum('estado', [
                'Pedido', 
                'En proceso', 
                'Enviado', 
                'En Entrega', 
                'Listo para Ser Recogido', 
                'Entregado', 
                'Cancelado'
            ])->default('Pedido');
            $table->text('observacion')->nullable();
            $table->timestamps();
        
            // Relación con Factura
            $table->foreign('id_factura')
                  ->references('id_factura')
                  ->on('facturas')
                  ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
