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
        Schema::table('CampaniaDestinatarios', function (Blueprint $table) {
            $table->foreign(['campania_id'], 'CampaniaDestinatarios_ibfk_1')->references(['id'])->on('Campanias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['cliente_id'], 'CampaniaDestinatarios_ibfk_2')->references(['id'])->on('Clientes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('CampaniaDestinatarios', function (Blueprint $table) {
            $table->dropForeign('CampaniaDestinatarios_ibfk_1');
            $table->dropForeign('CampaniaDestinatarios_ibfk_2');
        });
    }
};
