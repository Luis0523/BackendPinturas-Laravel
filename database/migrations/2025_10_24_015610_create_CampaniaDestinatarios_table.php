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
        Schema::create('CampaniaDestinatarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('campania_id')->index('campaniadestinatarios_index_31');
            $table->integer('cliente_id')->index('cliente_id');
            $table->string('estado', 20)->nullable()->default('PENDIENTE');
            $table->string('detalle')->nullable();
            $table->dateTime('enviado_en')->nullable();

            $table->unique(['campania_id', 'cliente_id'], 'campaniadestinatarios_index_32');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CampaniaDestinatarios');
    }
};
