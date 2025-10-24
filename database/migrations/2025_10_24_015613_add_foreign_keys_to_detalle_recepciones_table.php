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
        Schema::table('detalle_recepciones', function (Blueprint $table) {
            $table->foreign(['detalle_orden_id'], 'fk_detalle_recep_orden')->references(['id'])->on('detalle_orden_compra')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['recepcion_id'], 'fk_detalle_recep_recepcion')->references(['id'])->on('recepciones')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_recepciones', function (Blueprint $table) {
            $table->dropForeign('fk_detalle_recep_orden');
            $table->dropForeign('fk_detalle_recep_recepcion');
        });
    }
};
