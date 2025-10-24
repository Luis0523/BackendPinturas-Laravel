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
        Schema::table('inventariosucursal', function (Blueprint $table) {
            $table->foreign(['producto_presentacion_id'], 'fk_inventario_producto_presentacion')->references(['id'])->on('productopresentacion')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'fk_inventario_sucursal')->references(['id'])->on('sucursales')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventariosucursal', function (Blueprint $table) {
            $table->dropForeign('fk_inventario_producto_presentacion');
            $table->dropForeign('fk_inventario_sucursal');
        });
    }
};
