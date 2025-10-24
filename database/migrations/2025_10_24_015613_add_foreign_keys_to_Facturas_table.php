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
        Schema::table('Facturas', function (Blueprint $table) {
            $table->foreign(['cliente_id'], 'Facturas_ibfk_1')->references(['id'])->on('Clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'Facturas_ibfk_2')->references(['id'])->on('Usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'Facturas_ibfk_3')->references(['id'])->on('Sucursales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['anulada_por'], 'Facturas_ibfk_4')->references(['id'])->on('Usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Facturas', function (Blueprint $table) {
            $table->dropForeign('Facturas_ibfk_1');
            $table->dropForeign('Facturas_ibfk_2');
            $table->dropForeign('Facturas_ibfk_3');
            $table->dropForeign('Facturas_ibfk_4');
        });
    }
};
