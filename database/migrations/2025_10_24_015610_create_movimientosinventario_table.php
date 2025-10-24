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
        Schema::create('movimientosinventario', function (Blueprint $table) {
            $table->comment('Historial de todos los movimientos de inventario');
            $table->integer('id', true);
            $table->integer('sucursal_id')->index('idx_movimientos_sucursal');
            $table->integer('producto_presentacion_id')->index('idx_movimientos_producto');
            $table->string('tipo', 20)->index('idx_movimientos_tipo');
            $table->integer('cantidad');
            $table->string('referencia', 60)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent()->index('idx_movimientos_fecha');

            $table->index(['sucursal_id', 'tipo', 'created_at'], 'idx_movimientos_consulta');
            $table->index(['producto_presentacion_id', 'created_at'], 'idx_movimientos_producto_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientosinventario');
    }
};
