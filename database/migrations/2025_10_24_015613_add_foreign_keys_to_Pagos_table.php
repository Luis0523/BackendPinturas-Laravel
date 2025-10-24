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
        Schema::table('Pagos', function (Blueprint $table) {
            $table->foreign(['factura_id'], 'Pagos_ibfk_1')->references(['id'])->on('Facturas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo'], 'Pagos_ibfk_2')->references(['id'])->on('MediosPago')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Pagos', function (Blueprint $table) {
            $table->dropForeign('Pagos_ibfk_1');
            $table->dropForeign('Pagos_ibfk_2');
        });
    }
};
