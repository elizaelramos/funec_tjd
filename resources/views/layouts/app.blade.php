<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TJD · FUNEC')</title>

    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="/css/styles.css">

    @yield('head')
</head>
<body>
    <!-- Top strip: instituição, links etc. -->
    <div class="topstrip">
        <div class="wrap">
            <div class="org">
                <span class="dot"></span>
                <span>Tribunal de Justiça Desportiva do Futebol Estadual do Mato Grosso do Sul</span>
            </div>
            <div class="links">
                <a href="/">Portal público</a>
                @auth
                    <span style="color:#6b7384;">|</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#aeb9cb;cursor:pointer;font:inherit;padding:0;text-decoration:none;transition:color .15s;">Sair</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}">Área restrita</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Masthead: logo + títulos -->
    <div class="masthead">
        <div class="wrap">
            <div class="brandmark">
                <img src="/assets/logo-tjd-transparent.png" alt="Brasão TJD FUNEC">
                <div class="titles">
                    <div class="kicker">Tribunal de Justiça Desportiva</div>
                    <h1>TJD · FUNEC</h1>
                    <div class="place">Corumbá-MS</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main navigation -->
    <nav class="mainnav">
        <div class="wrap">
            <button class="menu-btn" onclick="this.nextElementSibling.classList.toggle('open')">
                <span>☰</span>
                <span>Menu</span>
            </button>
            <div class="nav-links">
                <!-- MENU PÚBLICO -->
                <a href="{{ route('processos.index') }}" class="@if(($active ?? null) === 'processos') active @endif">Processos</a>
                <a href="{{ route('decisoes') }}" class="@if(($active ?? null) === 'decisoes') active @endif">Decisões</a>
                <a href="{{ route('composicao') }}" class="@if(($active ?? null) === 'composicao') active @endif">Composição</a>
                <a href="{{ route('pauta.index') }}" class="@if(($active ?? null) === 'pauta') active @endif">Pautas</a>
                <a href="{{ route('punidos') }}" class="@if(($active ?? null) === 'punidos') active @endif">Punidos</a>
                <a href="{{ route('citacoes') }}" class="@if(($active ?? null) === 'citacoes') active @endif">Citações</a>
                <a href="{{ route('regulamento') }}" class="@if(($active ?? null) === 'regulamento') active @endif">Regulamento</a>
                <a href="{{ route('noticias.index') }}" class="@if(($active ?? null) === 'noticias') active @endif">Notícias</a>

                @auth
                    <!-- SEPARADOR -->
                    <div class="nav-separator"></div>

                    <!-- ACESSO AO PAINEL ADMINISTRATIVO -->
                    <a href="{{ route('admin.processos.index') }}" class="nav-admin-link">Painel administrativo</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="top"></div>
        <div class="wrap">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/">
                        <img src="/assets/logo-tjd.png" alt="Brasão TJD FUNEC">
                    </a>
                    <div class="titles">
                        <div class="name">TJD · FUNEC</div>
                        <p>Tribunal de Justiça Desportiva do Futebol Estadual do Mato Grosso do Sul.</p>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Processos</h4>
                    <a href="{{ route('processos.index') }}">Consultar</a>
                    <a href="{{ route('decisoes') }}">Decisões</a>
                </div>
                <div class="footer-col">
                    <h4>Informações</h4>
                    <a href="{{ route('composicao') }}">Composição</a>
                    <a href="{{ route('regulamento') }}">Regulamento</a>
                </div>
                <div class="footer-col">
                    <h4>Institucional</h4>
                    <a href="/">Home</a>
                    @auth
                        <a href="{{ route('admin.processos.index') }}">Admin</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="wrap">
                <span>&copy; 2026 TJD · FUNEC. Todos os direitos reservados.</span>
                <span>Desenvolvido com cuidado para o desporto profissional.</span>
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
