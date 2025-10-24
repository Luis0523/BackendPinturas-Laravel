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
        Schema::create('Campanias', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('titulo', 120);
            $table->text('cuerpo')->nullable();
            $table->integer('creado_por')->nullable()->index('creado_por');
            $table->dateTime('creado_en')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Campanias');
    }
};
