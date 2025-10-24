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
        Schema::create('Ingresos', function (Blueprint $table) {
            $table->comment('Al confirmar: generar MovimientosInventario tipo COMPRA (+cantidad)');
            $table->integer('id', true);
            $table->integer('proveedor_id');
            $table->integer('sucursal_id')->index('sucursal_id');
            $table->integer('usuario_id')->index('usuario_id');
            $table->dateTime('fecha')->nullable()->useCurrent()->index('ingresos_index_25');
            $table->string('documento', 60)->nullable();
            $table->decimal('total', 12)->nullable();

            $table->index(['proveedor_id', 'fecha'], 'ingresos_index_26');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Ingresos');
    }
};
