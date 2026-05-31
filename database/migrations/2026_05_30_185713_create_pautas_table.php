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
        Schema::create('pautas', function (Blueprint $table) {
            $table->id();
            $table->string('numero');                       // ex.: 5ª Sessão
            $table->string('titulo');                       // ex.: 5ª Sessão de Julgamento — Comissão Disciplinar
            $table->string('orgao_julgador');               // Comissão Disciplinar | Pleno do TJD
            $table->date('data');                           // data da sessão
            $table->time('hora');                           // horário de início
            $table->string('local')->nullable();            // local da sessão
            $table->string('situacao')->default('agendada'); // agendada | julgada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pautas');
    }
};
