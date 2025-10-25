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
        Schema::create('producto_presentacion', function (Blueprint $table) {
            $table->integer('id', true);

            // Foreign keys
            $table->integer('producto_id')->index('idx_producto_id');
            $table->integer('presentacion_id')->index('idx_presentacion_id');

            // Estado
            $table->boolean('activo')->nullable()->default(true)->index('idx_activo');

            // Timestamps personalizados
            $table->dateTime('createdAt')->nullable()->useCurrent();
            $table->dateTime('updatedAt')->useCurrentOnUpdate()->nullable()->useCurrent();

            // Índice único compuesto - Un producto solo puede tener una vez cada presentación
            $table->unique(['producto_id', 'presentacion_id'], 'unique_producto_presentacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_presentacion');
    }
};
