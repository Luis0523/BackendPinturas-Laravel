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
        Schema::create('Presentaciones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 40)->unique('nombre');
            $table->string('unidad_base', 20)->nullable();
            $table->decimal('factor_galon', 10, 5)->nullable();
            $table->boolean('activo')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Presentaciones');
    }
};
