<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela 'andamentos' — as etapas da tramitação de um processo
 * (denúncia recebida, citação publicada, julgado...).
 *
 * Cada andamento pertence a UM processo. É a "linha do tempo" mostrada
 * na página de detalhe do processo. Relação: processo (1) -> (N) andamentos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('andamentos', function (Blueprint $table) {
            $table->id();
            // chave estrangeira: liga ao processo. Se o processo for apagado,
            // seus andamentos são apagados junto (cascadeOnDelete).
            $table->foreignId('processo_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->dateTime('data')->nullable();
            $table->string('status')->default('done');   // done | current
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('andamentos');
    }
};
