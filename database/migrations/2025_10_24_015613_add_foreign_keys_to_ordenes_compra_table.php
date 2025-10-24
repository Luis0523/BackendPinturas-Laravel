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
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->foreign(['proveedor_id'], 'fk_ordenes_proveedor')->references(['id'])->on('proveedores')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'fk_ordenes_sucursal')->references(['id'])->on('sucursales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'fk_ordenes_usuario')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropForeign('fk_ordenes_proveedor');
            $table->dropForeign('fk_ordenes_sucursal');
            $table->dropForeign('fk_ordenes_usuario');
        });
    }
};
