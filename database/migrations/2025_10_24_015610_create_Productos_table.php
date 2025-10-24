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
        Schema::create('Productos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('categoria_id')->index('productos_index_4');
            $table->integer('marca_id')->nullable()->index('productos_index_5');
            $table->string('codigo_sku', 50)->nullable()->unique('codigo_sku');
            $table->string('descripcion');
            $table->string('tamano', 40)->nullable();
            $table->integer('duracion_anios')->nullable();
            $table->decimal('extension_m2', 10)->nullable();
            $table->string('color', 60)->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->dateTime('createdAt')->nullable()->useCurrent();
            $table->dateTime('updatedAt')->nullable();

            $table->index(['codigo_sku'], 'productos_index_6');
            $table->unique(['codigo_sku'], 'productos_pk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Productos');
    }
};
