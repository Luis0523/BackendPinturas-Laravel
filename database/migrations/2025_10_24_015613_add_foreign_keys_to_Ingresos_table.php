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
        Schema::table('Ingresos', function (Blueprint $table) {
            $table->foreign(['proveedor_id'], 'Ingresos_ibfk_1')->references(['id'])->on('Proveedores')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'Ingresos_ibfk_2')->references(['id'])->on('Sucursales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'Ingresos_ibfk_3')->references(['id'])->on('Usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Ingresos', function (Blueprint $table) {
            $table->dropForeign('Ingresos_ibfk_1');
            $table->dropForeign('Ingresos_ibfk_2');
            $table->dropForeign('Ingresos_ibfk_3');
        });
    }
};
