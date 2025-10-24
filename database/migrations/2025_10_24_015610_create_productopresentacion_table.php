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
        Schema::create('productopresentacion', function (Blueprint $table) {
            $table->comment('Tabla intermedia: relaciona productos con sus presentaciones disponibles para venta');
            $table->integer('id', true);
            $table->integer('producto_id')->index('idx_producto_id');
            $table->integer('presentacion_id')->index('idx_presentacion_id');
            $table->boolean('activo')->nullable()->default(true)->index('idx_activo');

            $table->unique(['producto_id', 'presentacion_id'], 'unique_producto_presentacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productopresentacion');
    }
};
