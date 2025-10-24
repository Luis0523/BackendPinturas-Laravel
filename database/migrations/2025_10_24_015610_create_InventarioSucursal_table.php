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
        Schema::create('InventarioSucursal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sucursal_id');
            $table->integer('producto_presentacion_id')->index('producto_presentacion_id');
            $table->integer('existencia')->default(0);
            $table->integer('minimo')->nullable()->default(0);

            $table->unique(['sucursal_id', 'producto_presentacion_id'], 'inventariosucursal_index_10');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('InventarioSucursal');
    }
};
