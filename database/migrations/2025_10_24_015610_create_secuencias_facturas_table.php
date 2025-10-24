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
        Schema::create('secuencias_facturas', function (Blueprint $table) {
            $table->string('serie', 10)->primary();
            $table->integer('ultimo_numero')->default(0);
            $table->string('descripcion', 100)->nullable();
            $table->boolean('activa')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secuencias_facturas');
    }
};
