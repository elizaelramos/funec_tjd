<?php

namespace App\Support;

/**
 * Detecção heurística de ameaças na camada de aplicação.
 *
 * IMPORTANTE: isto NÃO substitui um firewall/WAF/fail2ban no servidor. É uma
 * camada de visibilidade dentro do Laravel para flagrar padrões comuns de
 * injeção (SQLi/XSS), sondagem de rotas sensíveis e ferramentas de ataque
 * conhecidas. Falsos positivos/negativos são possíveis — use como alerta.
 */
class ThreatDetector
{
    /** Padrões típicos de SQL Injection (regex, case-insensitive). */
    private const SQLI_PATTERNS = [
        '/\bunion\b.{0,40}\bselect\b/i',
        '/\bselect\b.{0,80}\bfrom\b/i',
        '/\binsert\b.{0,40}\binto\b/i',
        '/\b(drop|truncate|alter)\b\s+(table|database)/i',
        '/\bor\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i', // or 1=1
        '/[\'"]\s*(or|and)\s+[\'"]?\d/i',              // ' or 1
        '/information_schema/i',
        '/\bsleep\s*\(/i',
        '/\bbenchmark\s*\(/i',
        '/\bload_file\s*\(/i',
        '/\bwaitfor\s+delay\b/i',
        '/(--|#)\s*$/m',                                // comentário SQL no fim
        '/;\s*(drop|delete|update|insert)\b/i',
    ];

    /** Padrões típicos de XSS. */
    private const XSS_PATTERNS = [
        '/<\s*script\b/i',
        '/<\s*iframe\b/i',
        '/<\s*img\b[^>]*\bonerror\s*=/i',
        '/\bon(error|load|click|mouseover)\s*=/i',
        '/javascript\s*:/i',
        '/<\s*svg\b[^>]*\bon\w+\s*=/i',
        '/document\.(cookie|location)/i',
        '/\beval\s*\(/i',
    ];

    /** Trechos de rota que indicam sondagem (path probing). */
    private const PROBE_NEEDLES = [
        '.env', 'wp-admin', 'wp-login', 'phpmyadmin', 'phpunit',
        '.git', '.aws', '.ssh', 'xmlrpc.php', 'eval-stdin',
        'shell.php', 'config.php', 'vendor/phpunit', 'solr/',
        'actuator/', 'cgi-bin', '.well-known/security', 'adminer',
    ];

    /** User-agents de ferramentas de ataque/scanners conhecidos. */
    private const BAD_BOT_NEEDLES = [
        'sqlmap', 'nikto', 'nmap', 'masscan', 'acunetix', 'nessus',
        'metasploit', 'dirbuster', 'gobuster', 'wpscan', 'hydra',
        'fimap', 'havij', 'zgrab', 'nuclei', 'whatweb', 'arachni',
        'qualys', 'openvas', 'libwww-perl', 'python-requests', 'go-http-client',
    ];

    /** Tokens que indicam bot legítimo/genérico (não malicioso). */
    private const BOT_NEEDLES = [
        'bot', 'crawler', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit',
        'googlebot', 'bingbot', 'duckduckbot', 'yandex', 'baidu', 'curl', 'wget',
        'headlesschrome', 'phantomjs', 'okhttp',
    ];

    /**
     * Analisa um valor de entrada. Retorna ['type'=>..., 'severity'=>...]
     * se detectar padrão de ataque, ou null caso pareça inofensivo.
     */
    public static function analyze(?string $value): ?array
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Decodifica para pegar payloads encodados (%27, etc.).
        $decoded = urldecode($value);

        foreach (self::SQLI_PATTERNS as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return ['type' => 'sql_injection', 'severity' => 'high'];
            }
        }

        foreach (self::XSS_PATTERNS as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return ['type' => 'xss_attempt', 'severity' => 'high'];
            }
        }

        return null;
    }

    public static function isBadBot(?string $userAgent): bool
    {
        return self::containsAny(strtolower((string) $userAgent), self::BAD_BOT_NEEDLES);
    }

    public static function isBot(?string $userAgent): bool
    {
        $ua = strtolower((string) $userAgent);
        if ($ua === '') {
            return true; // sem user-agent costuma ser script/bot
        }

        return self::containsAny($ua, self::BOT_NEEDLES) || self::isBadBot($ua);
    }

    public static function isPathProbe(?string $path): bool
    {
        return self::containsAny(strtolower((string) $path), self::PROBE_NEEDLES);
    }

    private static function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
