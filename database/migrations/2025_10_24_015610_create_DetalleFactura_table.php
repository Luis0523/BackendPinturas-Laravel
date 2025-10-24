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
        Schema::create('DetalleFactura', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('factura_id')->index('detallefactura_index_16');
            $table->integer('producto_presentacion_id')->index('producto_presentacion_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12);
            $table->decimal('descuento_pct_aplicado', 5)->nullable()->default(0);
            $table->decimal('subtotal', 12);

            $table->unique(['factura_id', 'producto_presentacion_id'], 'detallefactura_index_17');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DetalleFactura');
    }
};
