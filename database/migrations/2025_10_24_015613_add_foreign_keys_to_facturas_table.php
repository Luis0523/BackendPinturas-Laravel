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
        Schema::table('facturas', function (Blueprint $table) {
            $table->foreign(['anulada_por'], 'fk_facturas_anulada_por')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['cliente_id'], 'fk_facturas_cliente')->references(['id'])->on('clientes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'fk_facturas_sucursal')->references(['id'])->on('sucursales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'fk_facturas_usuario')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign('fk_facturas_anulada_por');
            $table->dropForeign('fk_facturas_cliente');
            $table->dropForeign('fk_facturas_sucursal');
            $table->dropForeign('fk_facturas_usuario');
        });
    }
};
