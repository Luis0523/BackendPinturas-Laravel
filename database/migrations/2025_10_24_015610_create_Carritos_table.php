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
        Schema::create('Carritos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cliente_id');
            $table->integer('sucursal_id')->nullable()->index('sucursal_id');
            $table->string('estado', 20)->nullable()->default('ABIERTO');
            $table->dateTime('creado_en')->nullable()->useCurrent();
            $table->dateTime('actualizado_en')->nullable();

            $table->index(['cliente_id', 'estado'], 'carritos_index_28');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Carritos');
    }
};
