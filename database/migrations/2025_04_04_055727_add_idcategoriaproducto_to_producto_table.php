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
    Schema::table('producto', function (Blueprint $table) {
        $table->unsignedBigInteger('id_categoriaProducto')->after('id_empresa');
        $table->foreign('id_categoriaProducto')
              ->references('id_categoriaProducto')
              ->on('categoria')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('producto', function (Blueprint $table) {
        $table->dropForeign(['id_categoriaProducto']);
        $table->dropColumn('id_categoriaProducto');
    });
}

};
