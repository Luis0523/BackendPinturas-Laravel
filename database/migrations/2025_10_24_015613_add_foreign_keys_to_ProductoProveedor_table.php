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
        Schema::table('ProductoProveedor', function (Blueprint $table) {
            $table->foreign(['proveedor_id'], 'ProductoProveedor_ibfk_1')->references(['id'])->on('Proveedores')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['producto_id'], 'ProductoProveedor_ibfk_2')->references(['id'])->on('Productos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ProductoProveedor', function (Blueprint $table) {
            $table->dropForeign('ProductoProveedor_ibfk_1');
            $table->dropForeign('ProductoProveedor_ibfk_2');
        });
    }
};
