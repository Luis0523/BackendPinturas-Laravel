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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->comment('Proveedores de productos');
            $table->integer('id', true);
            $table->string('nombre', 150)->index('idx_proveedores_nombre');
            $table->string('razon_social', 200)->nullable();
            $table->string('nit', 20)->nullable()->index('idx_proveedores_nit');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('direccion')->nullable();
            $table->string('contacto_principal', 100)->nullable();
            $table->boolean('activo')->nullable()->default(true)->index('idx_proveedores_activo');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
