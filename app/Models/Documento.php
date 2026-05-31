<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'processo_id',
        'pauta_id',
        'tipo',
        'titulo',
        'descricao',
        'arquivo',
        'nome_original',
        'data',
        'status_recurso',
        'usuario_id',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }

    public function pauta(): BelongsTo
    {
        return $this->belongsTo(Pauta::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
