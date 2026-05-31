<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('pauta_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('tipo'); // origem, citacao, ata, recurso, decisao_recurso
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('arquivo')->nullable(); // path relativo
            $table->string('nome_original')->nullable();
            $table->date('data')->nullable();
            $table->string('status_recurso')->nullable(); // aceito, negado (apenas para tipo=decisao_recurso)
            $table->foreignId('usuario_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
