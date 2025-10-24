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
        Schema::table('recepciones', function (Blueprint $table) {
            $table->foreign(['orden_compra_id'], 'fk_recepcion_orden')->references(['id'])->on('ordenes_compra')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'fk_recepcion_sucursal')->references(['id'])->on('sucursales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'fk_recepcion_usuario')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepciones', function (Blueprint $table) {
            $table->dropForeign('fk_recepcion_orden');
            $table->dropForeign('fk_recepcion_sucursal');
            $table->dropForeign('fk_recepcion_usuario');
        });
    }
};
