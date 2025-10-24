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
        Schema::create('ProductoPresentacion', function (Blueprint $table) {
            $table->comment('Catálogo vendible (Producto + Presentación)');
            $table->integer('id', true);
            $table->integer('producto_id');
            $table->integer('presentacion_id')->index('productopresentacion_index_8');
            $table->boolean('activo')->nullable()->default(true);

            $table->unique(['producto_id', 'presentacion_id'], 'productopresentacion_index_7');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductoPresentacion');
    }
};
