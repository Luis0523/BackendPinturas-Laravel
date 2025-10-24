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
        Schema::create('facturas', function (Blueprint $table) {
            $table->comment('Facturas de venta emitidas');
            $table->integer('id', true);
            $table->integer('numero');
            $table->string('serie', 10);
            $table->dateTime('fecha_emision')->useCurrent()->index('idx_facturas_fecha');
            $table->integer('cliente_id')->index('idx_facturas_cliente');
            $table->integer('usuario_id')->index('idx_facturas_usuario');
            $table->integer('sucursal_id')->index('idx_facturas_sucursal');
            $table->decimal('subtotal', 12)->default(0);
            $table->decimal('descuento_total', 12)->default(0);
            $table->decimal('total', 12)->default(0);
            $table->enum('estado', ['EMITIDA', 'ANULADA'])->default('EMITIDA')->index('idx_facturas_estado');
            $table->integer('anulada_por')->nullable()->index('fk_facturas_anulada_por');
            $table->dateTime('anulada_fecha')->nullable();
            $table->string('motivo_anulacion')->nullable();

            $table->index(['numero', 'serie'], 'idx_facturas_numero_serie');
            $table->unique(['numero', 'serie'], 'unique_factura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
