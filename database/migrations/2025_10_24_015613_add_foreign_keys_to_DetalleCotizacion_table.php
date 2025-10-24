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
        Schema::table('DetalleCotizacion', function (Blueprint $table) {
            $table->foreign(['cotizacion_id'], 'DetalleCotizacion_ibfk_1')->references(['id'])->on('Cotizaciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['producto_presentacion_id'], 'DetalleCotizacion_ibfk_2')->references(['id'])->on('ProductoPresentacion')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DetalleCotizacion', function (Blueprint $table) {
            $table->dropForeign('DetalleCotizacion_ibfk_1');
            $table->dropForeign('DetalleCotizacion_ibfk_2');
        });
    }
};
