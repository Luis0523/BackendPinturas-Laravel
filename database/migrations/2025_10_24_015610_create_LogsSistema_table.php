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
        Schema::create('LogsSistema', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('fecha_hora')->useCurrent()->index('logssistema_index_33');
            $table->integer('usuario_id')->nullable();
            $table->string('tabla_afectada', 100)->nullable();
            $table->string('accion', 50)->nullable();
            $table->string('registro_afectado_id', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('valores_antes')->nullable();
            $table->text('valores_despues')->nullable();
            $table->string('ip_origen', 64)->nullable();
            $table->string('dispositivo', 100)->nullable();
            $table->string('estado', 20)->nullable();

            $table->index(['usuario_id', 'fecha_hora'], 'logssistema_index_34');
            $table->index(['tabla_afectada', 'accion'], 'logssistema_index_35');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LogsSistema');
    }
};
