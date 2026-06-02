<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityEvent extends Model
{
    /** Só guardamos o instante do evento; não há updated_at. */
    public $timestamps = false;

    protected $fillable = [
        'type',
        'severity',
        'email',
        'ip',
        'user_agent',
        'path',
        'payload',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /** Rótulos legíveis para exibição no painel. */
    public const LABELS = [
        'login_failed'  => 'Falha de login',
        'lockout'       => 'Bloqueio por tentativas',
        'login_success' => 'Login realizado',
        'sql_injection' => 'Tentativa de SQL Injection',
        'xss_attempt'   => 'Tentativa de XSS',
        'path_probe'    => 'Sondagem de rota sensível',
        'bad_bot'       => 'Bot/ferramenta maliciosa',
    ];

    public function label(): string
    {
        return self::LABELS[$this->type] ?? $this->type;
    }
}
