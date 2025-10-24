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
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->comment('Órdenes de compra a proveedores');
            $table->integer('id', true);
            $table->integer('numero');
            $table->string('serie', 10)->default('OC');
            $table->integer('proveedor_id')->index('idx_ordenes_proveedor');
            $table->integer('sucursal_id')->index('idx_ordenes_sucursal');
            $table->integer('usuario_id')->index('idx_ordenes_usuario');
            $table->date('fecha_orden')->index('idx_ordenes_fecha');
            $table->date('fecha_entrega_estimada')->nullable();
            $table->decimal('subtotal', 12)->default(0);
            $table->decimal('descuento_total', 12)->default(0);
            $table->decimal('total', 12)->default(0);
            $table->enum('estado', ['PENDIENTE', 'PARCIAL', 'RECIBIDA', 'CANCELADA'])->default('PENDIENTE')->index('idx_ordenes_estado');
            $table->text('observaciones')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['numero', 'serie'], 'idx_ordenes_numero_serie');
            $table->unique(['numero', 'serie'], 'unique_orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
