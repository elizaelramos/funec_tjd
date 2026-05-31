<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Processo extends Model
{
    // Campos que podem ser preenchidos em massa (ex.: no seeder/formulário).
    protected $fillable = [
        'numero', 'assunto', 'competicao',
        'relator', 'enquadramento', 'denunciante', 'denunciado',
        'partida', 'clube', 'situacao', 'resultado', 'data_julgamento',
    ];

    protected $casts = [
        'data_julgamento' => 'date',
    ];

    /**
     * Um processo tem vários andamentos (a linha do tempo da tramitação),
     * já ordenados cronologicamente.
     */
    public function andamentos(): HasMany
    {
        return $this->hasMany(Andamento::class)->orderBy('ordem');
    }

    public function pautas(): BelongsToMany
    {
        return $this->belongsToMany(Pauta::class, 'pauta_processo')
            ->withTimestamps();
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    /** Atalho: o processo já foi julgado? */
    public function getJulgadoAttribute(): bool
    {
        return $this->situacao === 'julgado';
    }

    public function temCitacao(): bool
    {
        return $this->documentos()->where('tipo', 'citacao')->exists();
    }
}
