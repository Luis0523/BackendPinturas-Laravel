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
        Schema::table('detallefactura', function (Blueprint $table) {
            $table->foreign(['factura_id'], 'fk_detalle_factura')->references(['id'])->on('facturas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['producto_presentacion_id'], 'fk_detalle_producto_presentacion')->references(['id'])->on('productopresentacion')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detallefactura', function (Blueprint $table) {
            $table->dropForeign('fk_detalle_factura');
            $table->dropForeign('fk_detalle_producto_presentacion');
        });
    }
};
