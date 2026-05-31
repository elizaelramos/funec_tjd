<?php

/**
 * Traduções das mensagens de validação para português (pt_BR).
 * Cobre as regras usadas nos formulários do TJD. O :attribute é
 * substituído pelo nome amigável do campo (ver array 'attributes').
 */
return [
    'required' => 'O campo :attribute é obrigatório.',
    'string'   => 'O campo :attribute deve ser um texto.',
    'max'      => [
        'string' => 'O campo :attribute não pode ter mais que :max caracteres.',
    ],
    'unique'   => 'Este :attribute já está em uso.',
    'in'       => 'O valor selecionado para :attribute é inválido.',
    'date'     => 'O campo :attribute deve ser uma data válida.',

    // Nomes amigáveis dos campos (também sobrescritos pontualmente no controller).
    'attributes' => [
        'numero'          => 'número',
        'tipo'            => 'tipo',
        'assunto'         => 'assunto',
        'orgao_julgador'  => 'órgão julgador',
        'competicao'      => 'competição',
        'situacao'        => 'situação',
        'relator'         => 'relator',
        'enquadramento'   => 'enquadramento',
        'denunciante'     => 'denunciante',
        'denunciado'      => 'denunciado',
        'partida'         => 'partida',
        'clube'           => 'clube',
        'resultado'       => 'resultado',
        'data_julgamento' => 'data de julgamento',
    ],
];
