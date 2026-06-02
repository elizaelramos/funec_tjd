<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /** Períodos permitidos (em dias). */
    private const PERIODOS = [7, 30, 90];

    public function index(Request $request): View
    {
        Gate::authorize('super_admin');

        $periodo = (int) $request->query('periodo', 30);
        if (! in_array($periodo, self::PERIODOS, true)) {
            $periodo = 30;
        }
        $desde = Carbon::now()->subDays($periodo)->startOfDay();

        $acessos = AccessLog::where('created_at', '>=', $desde);

        // --- Resumo ---
        $totalAcessos    = (clone $acessos)->count();
        $visitantesUnicos = (clone $acessos)->distinct('ip')->count('ip');
        $acessosBots     = (clone $acessos)->where('is_bot', true)->count();
        $falhasLogin     = SecurityEvent::where('created_at', '>=', $desde)
            ->where('type', 'login_failed')->count();
        $alertasSeguranca = SecurityEvent::where('created_at', '>=', $desde)
            ->whereIn('type', ['sql_injection', 'xss_attempt', 'path_probe', 'bad_bot', 'lockout'])
            ->count();

        // --- Série diária para o gráfico ---
        $porDia = (clone $acessos)
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->groupBy('dia')->orderBy('dia')
            ->pluck('total', 'dia');

        $serie = $this->serieCompleta($porDia, $periodo);

        // --- Páginas mais acessadas ---
        $topPaginas = (clone $acessos)
            ->selectRaw('path, COUNT(*) as total')
            ->groupBy('path')->orderByDesc('total')
            ->limit(15)->get();

        // --- Usuários autenticados mais ativos ---
        $topUsuarios = (clone $acessos)
            ->whereNotNull('user_id')
            ->with('user:id,name,email')
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')->orderByDesc('total')
            ->limit(15)->get();

        // --- Buscas recentes (campo ?q=) ---
        $buscas = (clone $acessos)
            ->whereNotNull('search_term')
            ->where('search_term', '!=', '')
            ->orderByDesc('created_at')
            ->limit(40)
            ->get(['id', 'search_term', 'path', 'ip', 'user_id', 'created_at']);

        // Marca buscas que casam com padrão de injeção.
        foreach ($buscas as $busca) {
            $busca->suspeita = \App\Support\ThreatDetector::analyze($busca->search_term) !== null;
        }

        // --- Segurança: contadores por tipo ---
        $eventosPorTipo = SecurityEvent::where('created_at', '>=', $desde)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')->pluck('total', 'type');

        // --- Segurança: eventos recentes ---
        $eventosRecentes = SecurityEvent::where('created_at', '>=', $desde)
            ->orderByDesc('created_at')
            ->limit(50)->get();

        // --- IPs com mais eventos de segurança ---
        $ipsAtacantes = SecurityEvent::where('created_at', '>=', $desde)
            ->whereIn('type', ['sql_injection', 'xss_attempt', 'path_probe', 'bad_bot', 'login_failed'])
            ->whereNotNull('ip')
            ->selectRaw('ip, COUNT(*) as total')
            ->groupBy('ip')->orderByDesc('total')
            ->limit(15)->get();

        return view('admin.analytics.index', [
            'periodo'          => $periodo,
            'periodos'         => self::PERIODOS,
            'totalAcessos'     => $totalAcessos,
            'visitantesUnicos' => $visitantesUnicos,
            'acessosBots'      => $acessosBots,
            'falhasLogin'      => $falhasLogin,
            'alertasSeguranca' => $alertasSeguranca,
            'serieLabels'      => $serie->keys(),
            'serieValores'     => $serie->values(),
            'topPaginas'       => $topPaginas,
            'topUsuarios'      => $topUsuarios,
            'buscas'           => $buscas,
            'eventosPorTipo'   => $eventosPorTipo,
            'eventosRecentes'  => $eventosRecentes,
            'ipsAtacantes'     => $ipsAtacantes,
        ]);
    }

    /**
     * Preenche dias sem acesso com zero, para o gráfico não ter buracos.
     * Retorna chaves 'dd/mm' => total.
     */
    private function serieCompleta($porDia, int $periodo)
    {
        $resultado = collect();
        for ($i = $periodo - 1; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i);
            $chaveSql = $data->format('Y-m-d');
            $resultado->put($data->format('d/m'), (int) ($porDia[$chaveSql] ?? 0));
        }

        return $resultado;
    }
}
