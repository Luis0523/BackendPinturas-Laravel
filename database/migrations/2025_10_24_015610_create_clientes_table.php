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
        Schema::create('clientes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 150);
            $table->string('nit', 25)->nullable()->unique('nit');
            $table->string('email', 150)->nullable()->unique('email');
            $table->string('password_hash')->nullable();
            $table->boolean('opt_in_promos')->nullable()->default(false);
            $table->boolean('verificado')->nullable()->default(false);
            $table->string('telefono', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('gps_lat', 10, 6)->nullable();
            $table->decimal('gps_lng', 10, 6)->nullable();
            $table->dateTime('creado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
