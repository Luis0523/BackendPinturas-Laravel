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
        Schema::table('productos', function (Blueprint $table) {
            $table->foreign(['categoria_id'], 'fk_productos_categoria')->references(['id'])->on('categorias')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['marca_id'], 'fk_productos_marca')->references(['id'])->on('marcas')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign('fk_productos_categoria');
            $table->dropForeign('fk_productos_marca');
        });
    }
};
