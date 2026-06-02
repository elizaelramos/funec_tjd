<?php

namespace App\Providers;

use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('editor', fn(User $user) => $user->isAtLeast('editor'));
        Gate::define('admin', fn(User $user) => $user->isAtLeast('admin'));
        Gate::define('super_admin', fn(User $user) => $user->isAtLeast('super_admin'));

        $this->registerSecurityListeners();
    }

    /**
     * Eventos nativos de autenticação alimentam a tabela security_events,
     * usada pelo painel de Analytics. Nunca gravamos a senha — só o e-mail.
     */
    private function registerSecurityListeners(): void
    {
        Event::listen(function (Failed $event) {
            $this->logSecurity('login_failed', 'medium', [
                'email' => $event->credentials['email'] ?? null,
            ]);
        });

        Event::listen(function (Lockout $event) {
            $this->logSecurity('lockout', 'high', [
                'email' => $event->request->input('email'),
            ]);
        });

        Event::listen(function (Login $event) {
            $this->logSecurity('login_success', 'low', [
                'email' => $event->user->email ?? null,
            ]);
        });
    }

    private function logSecurity(string $type, string $severity, array $extra = []): void
    {
        try {
            SecurityEvent::create(array_merge([
                'type'       => $type,
                'severity'   => $severity,
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
                'path'       => '/' . ltrim(request()->path(), '/'),
                'created_at' => now(),
            ], $extra));
        } catch (\Throwable $e) {
            Log::warning("Falha ao registrar evento de segurança [{$type}]: " . $e->getMessage());
        }
    }
}
