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
        Schema::table('productopresentacion', function (Blueprint $table) {
            $table->foreign(['presentacion_id'], 'fk_pp_presentacion')->references(['id'])->on('presentaciones')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['producto_id'], 'fk_pp_producto')->references(['id'])->on('productos')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productopresentacion', function (Blueprint $table) {
            $table->dropForeign('fk_pp_presentacion');
            $table->dropForeign('fk_pp_producto');
        });
    }
};
