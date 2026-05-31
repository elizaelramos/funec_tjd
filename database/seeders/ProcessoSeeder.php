<?php

namespace Database\Seeders;

use App\Models\Processo;
use Illuminate\Database\Seeder;

/**
 * Popula a tabela 'processos' (e os andamentos do 031/2026) com os
 * dados que estavam fixos no protótipo HTML — agora vindos do banco.
 */
class ProcessoSeeder extends Seeder
{
    public function run(): void
    {
        // Evita duplicar se rodar o seeder mais de uma vez.
        Processo::query()->delete();

        // ---- Processos JÁ JULGADOS (4ª Sessão / página de decisões) ----
        $p031 = Processo::create([
            'numero'         => '031/2026',
            'assunto'        => 'Denúncia por agressão física a adversário',
            'competicao'     => 'Campeonato Corumbaense — Série A',
            'relator'        => 'Heliney de Miranda Jr.',
            'enquadramento'  => 'Art. 254-A, CBJD',
            'denunciante'    => 'Procuradoria do TJD',
            'denunciado'     => 'Atleta — Operário FC',
            'partida'        => 'Operário FC × Corumbaense EC',
            'clube'          => 'Operário FC',
            'situacao'       => 'julgado',
            'resultado'      => 'Suspensão · 4 partidas',
            'data_julgamento'=> '2026-05-21',
        ]);

        // Linha do tempo (andamentos) do processo 031/2026
        $p031->andamentos()->createMany([
            ['ordem' => 1, 'status' => 'done', 'titulo' => 'Denúncia recebida pela Procuradoria', 'data' => '2026-05-07 14:22', 'descricao' => 'Lavrada a denúncia com base na súmula da partida e no relatório do árbitro.'],
            ['ordem' => 2, 'status' => 'done', 'titulo' => 'Citação publicada', 'data' => '2026-05-08 09:10', 'descricao' => 'Citação publicada no portal, abrindo prazo de 2 dias úteis para defesa.'],
            ['ordem' => 3, 'status' => 'done', 'titulo' => 'Defesa prévia apresentada', 'data' => '2026-05-12 17:48', 'descricao' => 'Defesa protocolada pelo procurador do clube via acesso restrito.'],
            ['ordem' => 4, 'status' => 'done', 'titulo' => 'Incluído na pauta da 4ª Sessão', 'data' => '2026-05-14 00:00', 'descricao' => 'Processo distribuído ao relator e incluído na pauta de 21/05/2026.'],
            ['ordem' => 5, 'status' => 'current', 'titulo' => 'Julgado — decisão publicada', 'data' => '2026-05-21 20:05', 'descricao' => 'Por unanimidade, aplicada suspensão por 4 partidas.'],
        ]);

        Processo::create([
            'numero' => '030/2026',
            'assunto' => 'Absolvição — insuficiência de provas',
            'competicao' => 'Copa FUNEC de Futsal',
            'relator' => 'A. Firmino Sena', 'enquadramento' => 'Art. 243-F, CBJD',
            'denunciado' => 'Atleta — Náutico Corumbá', 'clube' => 'Náutico Corumbá',
            'situacao' => 'julgado', 'resultado' => 'Absolvição', 'data_julgamento' => '2026-05-21',
        ]);

        Processo::create([
            'numero' => '029/2026',
            'assunto' => 'Multa e suspensão de 2 partidas por conduta antidesportiva',
            'competicao' => 'Campeonato Municipal Sub-20',
            'relator' => 'J. S. Guerreiro', 'enquadramento' => 'Art. 258, CBJD',
            'denunciado' => 'Atleta — Pantanal FC', 'clube' => 'Pantanal FC',
            'situacao' => 'julgado', 'resultado' => 'Multa + 2 partidas', 'data_julgamento' => '2026-05-21',
        ]);

        Processo::create([
            'numero' => '027/2026',
            'assunto' => 'Perda de mando de campo por arremesso de objetos',
            'competicao' => 'Campeonato Corumbaense — Série A',
            'relator' => 'D. Vital do Rosário', 'enquadramento' => 'Art. 213, CBJD',
            'denunciado' => 'Ferroviário AC', 'clube' => 'Ferroviário AC',
            'situacao' => 'julgado', 'resultado' => 'Perda de mando', 'data_julgamento' => '2026-05-21',
        ]);

        // ---- Processos AGENDADOS (5ª Sessão / pauta — 04/06/2026) ----
        $agendados = [
            ['numero' => '026/2026', 'assunto' => 'Denúncia disciplinar', 'partida' => 'Operário FC × Corumbaense EC', 'competicao' => 'Campeonato Corumbaense — Série A', 'relator' => 'H. de Miranda Jr.', 'enquadramento' => 'Art. 254-A'],
            ['numero' => '028/2026', 'assunto' => 'Denúncia disciplinar', 'denunciado' => 'Atleta — Pantanal FC', 'clube' => 'Pantanal FC', 'competicao' => 'Campeonato Municipal Sub-20', 'relator' => 'D. Vital do Rosário', 'enquadramento' => 'Art. 258'],
            ['numero' => '032/2026', 'assunto' => 'Recurso disciplinar', 'denunciado' => 'Ferroviário AC', 'clube' => 'Ferroviário AC', 'competicao' => 'Campeonato Corumbaense — Série A', 'relator' => 'A. Firmino Sena', 'enquadramento' => 'Art. 213'],
            ['numero' => '033/2026', 'assunto' => 'Denúncia disciplinar', 'partida' => 'União Porto Geral × Náutico', 'competicao' => 'Copa FUNEC de Futsal', 'relator' => 'J. S. Guerreiro', 'enquadramento' => 'Art. 250'],
            ['numero' => '034/2026', 'assunto' => 'Denúncia disciplinar', 'denunciado' => 'Atleta — Dom Bosco FC', 'clube' => 'Dom Bosco FC', 'competicao' => 'Campeonato Corumbaense — Série A', 'relator' => 'H. de Miranda Jr.', 'enquadramento' => 'Art. 254'],
            ['numero' => '035/2026', 'assunto' => 'Denúncia disciplinar', 'denunciado' => 'Dirigente — Cristóvão Colombo EC', 'clube' => 'Cristóvão Colombo EC', 'competicao' => 'Citadino de Society', 'relator' => 'D. Vital do Rosário', 'enquadramento' => 'Art. 243'],
        ];

        foreach ($agendados as $dados) {
            Processo::create(array_merge([
                'situacao'       => 'agendado',
            ], $dados));
        }
    }
}
