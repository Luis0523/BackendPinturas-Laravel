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
        Schema::create('precios', function (Blueprint $table) {
            $table->comment('Precios de productos por sucursal con vigencia temporal');
            $table->integer('id', true);
            $table->integer('producto_presentacion_id')->index('idx_precios_producto_presentacion');
            $table->integer('sucursal_id')->nullable()->index('idx_precios_sucursal');
            $table->decimal('precio_venta', 12);
            $table->decimal('descuento_pct', 5)->nullable()->default(0);
            $table->dateTime('vigente_desde')->useCurrent();
            $table->dateTime('vigente_hasta')->nullable();
            $table->boolean('activo')->nullable()->default(true)->index('idx_precios_activo');

            $table->index(['producto_presentacion_id', 'sucursal_id', 'vigente_desde'], 'idx_precios_consulta');
            $table->index(['vigente_desde', 'vigente_hasta'], 'idx_precios_vigencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios');
    }
};
