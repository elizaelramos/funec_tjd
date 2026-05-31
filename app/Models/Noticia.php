<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noticia extends Model
{
    protected $fillable = [
        'titulo',
        'categoria',
        'resumo',
        'conteudo',
        'data',
        'link_externo',
        'imagem',
        'imagem_original',
        'status',
        'usuario_id',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function scopePublicada($query)
    {
        return $query->where('status', 'publicada')->orderByDesc('data');
    }
}
