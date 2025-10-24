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
        Schema::create('DetalleIngreso', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ingreso_id')->index('detalleingreso_index_27');
            $table->integer('producto_presentacion_id')->index('producto_presentacion_id');
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 12)->nullable();
            $table->decimal('subtotal', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DetalleIngreso');
    }
};
