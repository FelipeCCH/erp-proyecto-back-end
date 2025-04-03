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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('cedula_juridica', 20)->unique();
            $table->string('nombre', 255);
            $table->string('correo', 255);
            $table->enum('tipo', ['Micro', 'Pequeña', 'Mediana', 'Grande']);
            $table->string('ubicacionProvincia', 255);
            $table->string('ubicacionCanton', 255);
            $table->string('ubicacionDistrito', 255);
            $table->string('telefono', 20)->nullable();
            $table->enum('estado', ['Pendiente', 'Aprobada', 'Rechazada'])->default('Pendiente');
            // Usamos "fecha_registro" en lugar de los timestamps por defecto
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
