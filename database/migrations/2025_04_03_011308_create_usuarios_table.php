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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->foreignId('id_empresa')->constrained('empresa', 'id_empresa');
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('password');
            $table->enum('rol', ['Administrador', 'Usuario'])->default('Usuario');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
