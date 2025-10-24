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
        Schema::table('DetalleIngreso', function (Blueprint $table) {
            $table->foreign(['ingreso_id'], 'DetalleIngreso_ibfk_1')->references(['id'])->on('Ingresos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['producto_presentacion_id'], 'DetalleIngreso_ibfk_2')->references(['id'])->on('ProductoPresentacion')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DetalleIngreso', function (Blueprint $table) {
            $table->dropForeign('DetalleIngreso_ibfk_1');
            $table->dropForeign('DetalleIngreso_ibfk_2');
        });
    }
};
