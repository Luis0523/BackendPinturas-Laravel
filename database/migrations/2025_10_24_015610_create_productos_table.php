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
        Schema::create('productos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('categoria_id')->nullable()->index('idx_categoria_id');
            $table->integer('marca_id')->nullable()->index('idx_marca_id');
            $table->string('codigo_sku', 50)->unique('codigo_sku');
            $table->string('descripcion');
            $table->string('tamano', 40)->nullable();
            $table->integer('duracion_anios')->nullable();
            $table->decimal('extension_m2', 10)->nullable();
            $table->string('color', 60)->nullable();
            $table->boolean('activo')->nullable()->default(true)->index('idx_activo');
            $table->dateTime('createdAt')->nullable()->useCurrent();
            $table->dateTime('updatedAt')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['codigo_sku'], 'idx_codigo_sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
