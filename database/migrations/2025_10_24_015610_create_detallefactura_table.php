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
        Schema::create('detallefactura', function (Blueprint $table) {
            $table->comment('Detalle de productos vendidos por factura');
            $table->integer('id', true);
            $table->integer('factura_id')->index('idx_detalle_factura');
            $table->integer('producto_presentacion_id')->index('idx_detalle_producto');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12);
            $table->decimal('descuento_pct_aplicado', 5)->nullable()->default(0);
            $table->decimal('subtotal', 12);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallefactura');
    }
};
