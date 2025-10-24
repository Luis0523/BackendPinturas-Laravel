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
        Schema::table('Productos', function (Blueprint $table) {
            $table->foreign(['categoria_id'], 'Productos_ibfk_1')->references(['id'])->on('Categorias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['marca_id'], 'Productos_ibfk_2')->references(['id'])->on('Marcas')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Productos', function (Blueprint $table) {
            $table->dropForeign('Productos_ibfk_1');
            $table->dropForeign('Productos_ibfk_2');
        });
    }
};
