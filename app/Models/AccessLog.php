<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    /** Só guardamos o instante do acesso; não há updated_at. */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip',
        'method',
        'path',
        'route_name',
        'status_code',
        'user_agent',
        'referer',
        'search_term',
        'is_bot',
        'created_at',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
