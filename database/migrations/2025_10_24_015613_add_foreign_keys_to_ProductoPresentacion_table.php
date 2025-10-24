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
        Schema::table('ProductoPresentacion', function (Blueprint $table) {
            $table->foreign(['producto_id'], 'ProductoPresentacion_ibfk_1')->references(['id'])->on('Productos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['presentacion_id'], 'ProductoPresentacion_ibfk_2')->references(['id'])->on('Presentaciones')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ProductoPresentacion', function (Blueprint $table) {
            $table->dropForeign('ProductoPresentacion_ibfk_1');
            $table->dropForeign('ProductoPresentacion_ibfk_2');
        });
    }
};
