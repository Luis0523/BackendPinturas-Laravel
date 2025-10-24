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
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 120);
            $table->string('dpi', 20)->unique('dpi');
            $table->string('email', 150)->unique('email');
            $table->string('password_hash');
            $table->integer('rol_id');
            $table->integer('sucursal_id')->nullable()->index('sucursal_id');
            $table->boolean('activo')->nullable()->default(true);
            $table->dateTime('creado_en')->nullable()->useCurrent();

            $table->index(['rol_id', 'sucursal_id'], 'usuarios_index_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};
