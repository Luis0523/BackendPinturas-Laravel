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
        Schema::create('detalle_recepciones', function (Blueprint $table) {
            $table->comment('Detalle de productos recibidos en cada recepción');
            $table->integer('id', true);
            $table->integer('recepcion_id')->index('idx_detalle_recep_recepcion');
            $table->integer('detalle_orden_id')->index('idx_detalle_recep_orden');
            $table->integer('cantidad_recibida');
            $table->string('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_recepciones');
    }
};
