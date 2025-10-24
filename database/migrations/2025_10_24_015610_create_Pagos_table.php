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
        Schema::create('Pagos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('factura_id')->index('pagos_index_18');
            $table->integer('tipo')->unique('pagos_index_19');
            $table->decimal('monto', 12);
            $table->string('referencia', 80)->nullable();
            $table->string('entidad', 80)->nullable();
            $table->string('transaccion_gateway_id', 80)->nullable();
            $table->string('autorizado_por', 120)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Pagos');
    }
};
