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
        Schema::create('ProductoProveedor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('proveedor_id');
            $table->integer('producto_id')->index('producto_id');
            $table->string('codigo_prov', 60)->nullable();
            $table->decimal('precio_compra', 12)->nullable();
            $table->boolean('activo')->nullable()->default(true);

            $table->unique(['proveedor_id', 'producto_id'], 'productoproveedor_index_24');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductoProveedor');
    }
};
