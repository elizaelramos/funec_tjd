<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pauta extends Model
{
    protected $fillable = [
        'numero', 'titulo', 'orgao_julgador', 'data', 'hora', 'local', 'situacao',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function processos(): BelongsToMany
    {
        return $this->belongsToMany(Processo::class, 'pauta_processo')
            ->withTimestamps();
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }
}
