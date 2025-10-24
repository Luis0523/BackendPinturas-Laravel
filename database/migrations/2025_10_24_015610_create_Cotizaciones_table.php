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
        Schema::create('Cotizaciones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('numero');
            $table->string('serie', 10);
            $table->dateTime('fecha')->useCurrent();
            $table->integer('cliente_id')->nullable();
            $table->integer('usuario_id')->index('usuario_id');
            $table->integer('sucursal_id')->index('sucursal_id');
            $table->decimal('subtotal', 12)->default(0);
            $table->decimal('descuento_total', 12)->default(0);
            $table->decimal('total', 12)->default(0);
            $table->dateTime('vigente_hasta')->nullable();
            $table->string('estado', 20)->nullable()->default('ABIERTA');

            $table->unique(['numero', 'serie'], 'cotizaciones_index_20');
            $table->index(['cliente_id', 'fecha'], 'cotizaciones_index_21');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Cotizaciones');
    }
};
