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
        Schema::create('MediosPago', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('efectivo', 12)->default(0);
            $table->decimal('tarjeta', 12)->default(0);
            $table->decimal('cheque', 12)->default(0);
            $table->decimal('transferencia', 12)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MediosPago');
    }
};
