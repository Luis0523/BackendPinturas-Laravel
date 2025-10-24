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
        Schema::create('detalle_orden_compra', function (Blueprint $table) {
            $table->comment('Detalle de productos en órdenes de compra');
            $table->integer('id', true);
            $table->integer('orden_compra_id')->index('idx_detalle_orden');
            $table->integer('producto_presentacion_id')->index('idx_detalle_producto_pres');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12);
            $table->decimal('descuento_pct', 5)->nullable()->default(0);
            $table->decimal('subtotal', 12);
            $table->integer('cantidad_recibida')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_compra');
    }
};
