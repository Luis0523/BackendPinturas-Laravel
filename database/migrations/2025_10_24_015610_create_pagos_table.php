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
        Schema::create('pagos', function (Blueprint $table) {
            $table->comment('Pagos recibidos por factura (permite múltiples pagos por factura)');
            $table->integer('id', true);
            $table->integer('factura_id')->index('idx_pagos_factura');
            $table->enum('tipo', ['EFECTIVO', 'TARJETA_DEBITO', 'TARJETA_CREDITO', 'CHEQUE', 'TRANSFERENCIA', 'DEPOSITO'])->index('idx_pagos_tipo');
            $table->decimal('monto', 12);
            $table->string('referencia', 80)->nullable()->comment('Número de cheque, voucher, etc.');
            $table->string('entidad', 80)->nullable()->index('idx_pagos_entidad')->comment('Banco o procesador de pago');
            $table->string('transaccion_gateway_id', 80)->nullable()->comment('ID de transacción del gateway');
            $table->string('autorizado_por', 120)->nullable()->comment('Persona que autorizó el pago');
            $table->dateTime('created_at')->nullable()->useCurrent()->index('idx_pagos_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
