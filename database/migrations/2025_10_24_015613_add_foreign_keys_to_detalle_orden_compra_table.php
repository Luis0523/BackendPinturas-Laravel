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
        Schema::table('detalle_orden_compra', function (Blueprint $table) {
            $table->foreign(['orden_compra_id'], 'fk_detalle_orden')->references(['id'])->on('ordenes_compra')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['producto_presentacion_id'], 'fk_detalle_producto_pres')->references(['id'])->on('productopresentacion')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_orden_compra', function (Blueprint $table) {
            $table->dropForeign('fk_detalle_orden');
            $table->dropForeign('fk_detalle_producto_pres');
        });
    }
};
