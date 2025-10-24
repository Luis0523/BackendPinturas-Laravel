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
        Schema::create('inventariosucursal', function (Blueprint $table) {
            $table->comment('Stock actual de productos por sucursal');
            $table->integer('id', true);
            $table->integer('sucursal_id')->index('idx_inventario_sucursal');
            $table->integer('producto_presentacion_id')->index('idx_inventario_producto');
            $table->integer('existencia')->default(0)->index('idx_inventario_existencia');
            $table->integer('minimo')->nullable()->default(0);

            $table->index(['existencia', 'minimo'], 'idx_inventario_alerta');
            $table->unique(['sucursal_id', 'producto_presentacion_id'], 'unique_sucursal_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventariosucursal');
    }
};
