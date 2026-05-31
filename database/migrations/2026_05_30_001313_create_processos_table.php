<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela 'processos' — o coração do TJD.
 * Cada linha é um processo disciplinar (denúncia ou recurso).
 *
 * Obs.: por enquanto atleta/clube/competição são texto, pois os módulos
 * de cadastro ainda não existem. No futuro viram chaves estrangeiras.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();          // ex.: 031/2026
            $table->string('tipo');                       // denuncia | recurso
            $table->string('assunto');                    // descrição curta
            $table->string('orgao_julgador');             // Comissão Disciplinar | Pleno do TJD
            $table->string('competicao');
            $table->string('relator')->nullable();
            $table->string('enquadramento')->nullable();  // ex.: Art. 254-A, CBJD
            $table->string('denunciante')->nullable();
            $table->string('denunciado')->nullable();
            $table->string('partida')->nullable();
            $table->string('clube')->nullable();
            $table->string('situacao')->default('em_tramitacao'); // agendado | em_tramitacao | julgado | arquivado
            $table->string('resultado')->nullable();      // ex.: Suspensão · 4 partidas
            $table->date('data_julgamento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
