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
        Schema::create('CampaniaAdjuntos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('campania_id')->index('campania_id');
            $table->string('tipo', 20);
            $table->string('url');
            $table->string('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CampaniaAdjuntos');
    }
};
