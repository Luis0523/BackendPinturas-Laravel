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
        Schema::table('Cotizaciones', function (Blueprint $table) {
            $table->foreign(['cliente_id'], 'Cotizaciones_ibfk_1')->references(['id'])->on('Clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'Cotizaciones_ibfk_2')->references(['id'])->on('Usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'Cotizaciones_ibfk_3')->references(['id'])->on('Sucursales')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Cotizaciones', function (Blueprint $table) {
            $table->dropForeign('Cotizaciones_ibfk_1');
            $table->dropForeign('Cotizaciones_ibfk_2');
            $table->dropForeign('Cotizaciones_ibfk_3');
        });
    }
};
