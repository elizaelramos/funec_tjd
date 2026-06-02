<?php

namespace App\Console\Commands;

use App\Models\AccessLog;
use App\Models\SecurityEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class PruneAnalytics extends Command
{
    protected $signature = 'analytics:prune
                            {--access-days=90 : Dias de retenção dos logs de acesso}
                            {--security-days=180 : Dias de retenção dos eventos de segurança}';

    protected $description = 'Remove logs de acesso e eventos de segurança antigos do painel de Analytics';

    public function handle(): int
    {
        $acessoDias = (int) $this->option('access-days');
        $segurancaDias = (int) $this->option('security-days');

        $acessos = AccessLog::where('created_at', '<', Carbon::now()->subDays($acessoDias))->delete();
        $eventos = SecurityEvent::where('created_at', '<', Carbon::now()->subDays($segurancaDias))->delete();

        $this->info("Removidos {$acessos} log(s) de acesso (> {$acessoDias} dias).");
        $this->info("Removidos {$eventos} evento(s) de segurança (> {$segurancaDias} dias).");

        return self::SUCCESS;
    }
}
