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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign(['rol_id'], 'fk_usuarios_rol')->references(['id'])->on('roles')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'fk_usuarios_sucursal')->references(['id'])->on('sucursales')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('fk_usuarios_rol');
            $table->dropForeign('fk_usuarios_sucursal');
        });
    }
};
