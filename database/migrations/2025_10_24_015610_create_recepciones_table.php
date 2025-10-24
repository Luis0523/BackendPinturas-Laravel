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
        Schema::create('recepciones', function (Blueprint $table) {
            $table->comment('Recepciones de productos de órdenes de compra');
            $table->integer('id', true);
            $table->integer('orden_compra_id')->index('idx_recepcion_orden');
            $table->integer('sucursal_id')->index('idx_recepcion_sucursal');
            $table->integer('usuario_id')->index('idx_recepcion_usuario');
            $table->timestamp('fecha_recepcion')->nullable()->useCurrent()->index('idx_recepcion_fecha');
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones');
    }
};
