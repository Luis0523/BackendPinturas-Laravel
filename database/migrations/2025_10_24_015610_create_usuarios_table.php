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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 120);
            $table->string('dpi', 20)->unique('dpi');
            $table->string('email', 150)->unique('email');
            $table->string('password_hash');
            $table->integer('rol_id')->index('idx_usuarios_rol');
            $table->integer('sucursal_id')->nullable()->index('idx_usuarios_sucursal');
            $table->boolean('activo')->nullable()->default(true)->index('idx_usuarios_activo');
            $table->dateTime('creado_en')->nullable()->useCurrent();

            $table->index(['dpi'], 'idx_usuarios_dpi');
            $table->index(['email'], 'idx_usuarios_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
