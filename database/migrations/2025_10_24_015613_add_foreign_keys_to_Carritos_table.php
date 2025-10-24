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
        Schema::table('Carritos', function (Blueprint $table) {
            $table->foreign(['cliente_id'], 'Carritos_ibfk_1')->references(['id'])->on('Clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sucursal_id'], 'Carritos_ibfk_2')->references(['id'])->on('Sucursales')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Carritos', function (Blueprint $table) {
            $table->dropForeign('Carritos_ibfk_1');
            $table->dropForeign('Carritos_ibfk_2');
        });
    }
};
