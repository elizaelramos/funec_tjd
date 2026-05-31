<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'orgao_julgador']);
        });
    }

    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->string('tipo')->after('numero');
            $table->string('orgao_julgador')->after('assunto');
        });
    }
};
