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
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('categoria');
            $table->text('resumo')->nullable();
            $table->longText('conteudo')->nullable();
            $table->date('data');
            $table->string('link_externo')->nullable();
            $table->string('imagem')->nullable();
            $table->string('imagem_original')->nullable();
            $table->string('status')->default('rascunho');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
