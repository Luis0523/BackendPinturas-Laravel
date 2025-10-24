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
        Schema::table('CampaniaAdjuntos', function (Blueprint $table) {
            $table->foreign(['campania_id'], 'CampaniaAdjuntos_ibfk_1')->references(['id'])->on('Campanias')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('CampaniaAdjuntos', function (Blueprint $table) {
            $table->dropForeign('CampaniaAdjuntos_ibfk_1');
        });
    }
};
