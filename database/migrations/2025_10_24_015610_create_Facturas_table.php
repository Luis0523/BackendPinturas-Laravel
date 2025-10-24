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
        Schema::create('Facturas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('numero');
            $table->string('serie', 10);
            $table->dateTime('fecha_emision')->useCurrent()->index('facturas_index_14');
            $table->integer('cliente_id');
            $table->integer('usuario_id')->index('usuario_id');
            $table->integer('sucursal_id')->index('sucursal_id');
            $table->decimal('subtotal', 12)->default(0);
            $table->decimal('descuento_total', 12)->default(0);
            $table->decimal('total', 12)->default(0);
            $table->enum('estado', ['EMITIDA', 'ANULADA'])->default('EMITIDA');
            $table->integer('anulada_por')->nullable()->index('anulada_por');
            $table->dateTime('anulada_fecha')->nullable();
            $table->string('motivo_anulacion')->nullable();

            $table->unique(['numero', 'serie'], 'facturas_index_13');
            $table->index(['cliente_id', 'fecha_emision'], 'facturas_index_15');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Facturas');
    }
};
