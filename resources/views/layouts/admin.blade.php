<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Painel administrativo · TJD · FUNEC')</title>

    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="/css/styles.css">

    @yield('head')
</head>
<body>
    <!-- Top strip: instituição + voltar ao site -->
    <div class="topstrip">
        <div class="wrap">
            <div class="org">
                <span class="dot"></span>
                <span>Painel administrativo — TJD · FUNEC</span>
            </div>
            <div class="links">
                <a href="/">Ver site público</a>
                <span style="color:#6b7384;">|</span>
                <span style="color:#aeb9cb;">{{ Auth::user()->name }}</span>
                <span style="color:#6b7384;">|</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#aeb9cb;cursor:pointer;font:inherit;padding:0;text-decoration:none;transition:color .15s;">Sair</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Masthead: logo + título do painel -->
    <div class="masthead">
        <div class="wrap">
            <div class="brandmark">
                <img src="/assets/logo-tjd-transparent.png" alt="Brasão TJD FUNEC">
                <div class="titles">
                    <div class="kicker">Tribunal de Justiça Desportiva</div>
                    <h1>Painel administrativo</h1>
                    <div class="place">TJD · FUNEC — Corumbá-MS</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegação do painel -->
    <nav class="mainnav">
        <div class="wrap">
            <button class="menu-btn" onclick="this.nextElementSibling.classList.toggle('open')">
                <span>☰</span>
                <span>Menu</span>
            </button>
            <div class="nav-links">
                <a href="{{ route('admin.processos.index') }}" class="@if(request()->routeIs('admin.processos.*')) active @endif">Processos</a>
                <a href="{{ route('admin.pautas.index') }}" class="@if(request()->routeIs('admin.pautas.*')) active @endif">Pautas</a>
                <a href="{{ route('admin.noticias.index') }}" class="@if(request()->routeIs('admin.noticias.*')) active @endif">Notícias</a>
                @can('admin')
                    <a href="{{ route('admin.usuarios.index') }}" class="@if(request()->routeIs('admin.usuarios.*')) active @endif">Usuários</a>
                @endcan
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="top"></div>
        <div class="footer-bottom">
            <div class="wrap">
                <span>&copy; 2026 TJD · FUNEC — Painel administrativo.</span>
                <span><a href="/" style="color:inherit;">Voltar ao site público</a></span>
            </div>
        </div>
    </footer>

    <script>
        // Fechar menu mobile ao clicar em um link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                document.querySelector('.nav-links').classList.remove('open');
            });
        });
    </script>

    @vite(['resources/js/app.js'])
</body>
</html>
