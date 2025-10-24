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
        Schema::create('CarritoItems', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('carrito_id')->index('carritoitems_index_29');
            $table->integer('producto_presentacion_id')->index('producto_presentacion_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12)->nullable();
            $table->decimal('descuento_pct', 5)->nullable()->default(0);
            $table->decimal('subtotal', 12)->nullable();

            $table->unique(['carrito_id', 'producto_presentacion_id'], 'carritoitems_index_30');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CarritoItems');
    }
};
