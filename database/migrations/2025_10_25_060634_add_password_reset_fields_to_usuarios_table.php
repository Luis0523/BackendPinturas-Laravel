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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dateTime('ultimo_acceso')->nullable()->after('activo');
            $table->string('reset_token', 500)->nullable()->after('ultimo_acceso');
            $table->dateTime('reset_token_expira')->nullable()->after('reset_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['ultimo_acceso', 'reset_token', 'reset_token_expira']);
        });
    }
};
