<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Andamento extends Model
{
    protected $fillable = [
        'processo_id', 'titulo', 'descricao', 'data', 'status', 'ordem',
    ];

    protected $casts = [
        'data' => 'datetime',
    ];

    /**
     * Cada andamento pertence a um processo (o lado inverso de hasMany).
     */
    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }
}
