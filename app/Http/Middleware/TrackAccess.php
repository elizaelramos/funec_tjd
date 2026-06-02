<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use App\Models\SecurityEvent;
use App\Support\ThreatDetector;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackAccess
{
    /** Prefixos ignorados (assets e o próprio painel de analytics). */
    private const IGNORE_PREFIXES = [
        'css/', 'js/', 'assets/', 'build/', 'storage/',
        'favicon', 'robots.txt', 'admin/analytics',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Roda DEPOIS da resposta ser enviada ao cliente, então registrar não
     * atrasa a navegação e já temos o status HTTP final.
     */
    public function terminate(Request $request, Response $response): void
    {
        try {
            $path = ltrim($request->path(), '/');

            if ($this->shouldIgnore($path)) {
                return;
            }

            $userAgent = (string) $request->userAgent();
            $status = $response->getStatusCode();
            $search = $request->query('q');

            AccessLog::create([
                'user_id'     => Auth::id(),
                'ip'          => $request->ip(),
                'method'      => $request->method(),
                'path'        => Str::limit('/' . $path, 250, ''),
                'route_name'  => optional($request->route())->getName(),
                'status_code' => $status,
                'user_agent'  => $userAgent !== '' ? $userAgent : null,
                'referer'     => $request->headers->get('referer'),
                'search_term' => is_string($search) ? Str::limit($search, 250, '') : null,
                'is_bot'      => ThreatDetector::isBot($userAgent),
                'created_at'  => now(),
            ]);

            $this->detectThreats($request, $path, $userAgent, $search);
        } catch (\Throwable $e) {
            // Nunca deixar o monitoramento quebrar a aplicação.
            Log::warning('TrackAccess falhou: ' . $e->getMessage());
        }
    }

    private function detectThreats(Request $request, string $path, string $userAgent, $search): void
    {
        $ip = $request->ip();

        // 1) Padrões de injeção em qualquer parâmetro de query.
        foreach ($request->query() as $key => $value) {
            if (! is_string($value)) {
                continue;
            }
            if ($threat = ThreatDetector::analyze($value)) {
                $this->record($threat['type'], $threat['severity'], $ip, $userAgent, '/' . $path, "{$key}={$value}");
            }
        }

        // 2) Sondagem de rotas sensíveis (.env, wp-admin, etc.).
        if (ThreatDetector::isPathProbe($path)) {
            $this->record('path_probe', 'medium', $ip, $userAgent, '/' . $path, null);
        }

        // 3) Ferramenta de ataque conhecida no user-agent.
        if (ThreatDetector::isBadBot($userAgent)) {
            $this->record('bad_bot', 'high', $ip, $userAgent, '/' . $path, null);
        }
    }

    private function record(string $type, string $severity, ?string $ip, string $userAgent, string $path, ?string $payload): void
    {
        SecurityEvent::create([
            'type'       => $type,
            'severity'   => $severity,
            'ip'         => $ip,
            'user_agent' => $userAgent !== '' ? $userAgent : null,
            'path'       => Str::limit($path, 250, ''),
            'payload'    => $payload !== null ? Str::limit($payload, 1000, '') : null,
            'created_at' => now(),
        ]);
    }

    private function shouldIgnore(string $path): bool
    {
        foreach (self::IGNORE_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
