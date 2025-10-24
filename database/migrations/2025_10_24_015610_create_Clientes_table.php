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
        Schema::create('Clientes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 150);
            $table->string('nit', 25)->nullable()->unique('nit');
            $table->string('email', 150)->nullable()->index('clientes_index_2');
            $table->string('password_hash')->nullable();
            $table->boolean('opt_in_promos')->nullable()->default(false);
            $table->boolean('verificado')->nullable()->default(false);
            $table->string('telefono', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('gps_lat', 10, 6)->nullable();
            $table->decimal('gps_lng', 10, 6)->nullable();
            $table->dateTime('creado_en')->nullable()->useCurrent();

            $table->index(['gps_lat', 'gps_lng'], 'clientes_index_3');
            $table->unique(['email'], 'email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Clientes');
    }
};
