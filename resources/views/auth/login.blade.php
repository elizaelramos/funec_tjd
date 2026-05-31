@extends('layouts.app')

@section('title', 'Acesso Restrito — TJD · FUNEC')

@section('head')
<style>
body{background:var(--navy-950);min-height:100vh;}
.auth{min-height:100vh;display:grid;grid-template-columns:1fr 1fr;}
.auth .brand{position:relative;background:var(--navy-900);color:#fff;padding:52px 56px;display:flex;flex-direction:column;overflow:hidden;}
.auth .brand::before{content:"";position:absolute;inset:0;background:
  radial-gradient(700px 380px at 80% -10%, rgba(195,154,63,.22), transparent 60%),
  radial-gradient(600px 460px at -10% 120%, rgba(36,76,135,.5), transparent 60%);}
.auth .brand .top{position:relative;display:flex;align-items:center;gap:14px;}
.auth .brand .top img{height:54px;}
.auth .brand .top .k{font-size:.68rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-bright);}
.auth .brand .top .n{font-family:var(--serif);font-size:1.2rem;font-weight:700;}
.auth .brand .mid{position:relative;margin-top:auto;margin-bottom:auto;}
.auth .brand .mid h2{color:#fff;font-size:2rem;line-height:1.15;max-width:18ch;}
.auth .brand .mid p{color:#c4d0e2;margin-top:14px;max-width:42ch;line-height:1.6;}
.roles{position:relative;display:flex;flex-direction:column;gap:10px;margin-top:26px;}
.role{display:flex;gap:12px;align-items:center;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:var(--radius);padding:12px 16px;}
.role .ic{width:34px;height:34px;border-radius:8px;background:rgba(195,154,63,.18);color:var(--gold-bright);display:flex;align-items:center;justify-content:center;flex:none;}
.role .rt{font-size:.92rem;font-weight:600;color:#eaf0fa;}
.role .rd{font-size:.78rem;color:#9fb0cc;}

.auth .panel{background:var(--paper);display:flex;align-items:center;justify-content:center;padding:40px;}
.login-card{width:100%;max-width:380px;}
.login-card .back{font-size:.84rem;color:var(--navy-700);font-weight:600;display:inline-flex;gap:6px;margin-bottom:26px;}
.login-card h1{font-size:1.7rem;}
.login-card .sub{color:var(--muted);margin-top:6px;font-size:.92rem;}
.login-card form{margin-top:26px;display:flex;flex-direction:column;gap:16px;}
.login-card label{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-soft);display:block;margin-bottom:6px;}
.login-card input{width:100%;font-family:inherit;font-size:1rem;padding:12px 14px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface);color:var(--ink);outline:none;transition:border-color .15s, box-shadow .15s;}
.login-card input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(195,154,63,.16);}
.login-card input.error{border-color:var(--danger);}
.login-card .error-msg{font-size:.8rem;color:var(--danger);margin-top:4px;}
.login-card .row-between{display:flex;justify-content:space-between;align-items:center;font-size:.84rem;}
.login-card .row-between a{color:var(--navy-700);font-weight:600;}
.login-card .btn{justify-content:center;width:100%;padding:13px;}
.login-card .note{font-size:.8rem;color:var(--muted);text-align:center;margin-top:18px;line-height:1.5;}
.flash-status{background:#e8f3ec;border:1px solid #c5e2d0;color:#2f7d51;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;font-size:.9rem;}
@media (max-width:860px){ .auth{grid-template-columns:1fr;} .auth .brand{display:none;} }
</style>
@endsection

@section('content')
<div class="auth" style="margin:0;padding:0;min-height:100vh;">
  <div class="brand">
    <div class="top"><img src="/assets/logo-tjd-transparent.png" alt="Brasão TJD FUNEC"><div><div class="k">Tribunal de Justiça Desportiva</div><div class="n">TJD · FUNEC</div></div></div>
    <div class="mid">
      <h2>Área restrita do Tribunal</h2>
      <p>Ambiente seguro para a gestão de processos disciplinares, emissão de citações, distribuição e registro de decisões.</p>
      <div class="roles">
        <div class="role"><span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M14 3v5h5"/></svg></span><div><div class="rt">Secretaria do TJD</div><div class="rd">Cadastra processos, publica pautas e decisões</div></div></div>
        <div class="role"><span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z"/></svg></span><div><div class="rt">Procuradoria</div><div class="rd">Emite denúncias e citações</div></div></div>
        <div class="role"><span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16M5 8h14M7 8l-3 6a3 3 0 0 0 6 0zM17 8l-3 6a3 3 0 0 0 6 0z"/></svg></span><div><div class="rt">Auditores</div><div class="rd">Relatam e votam nos processos</div></div></div>
        <div class="role"><span class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-6 8-6s8 2 8 6"/></svg></span><div><div class="rt">Clubes e advogados</div><div class="rd">Protocolam defesas e acompanham processos</div></div></div>
      </div>
    </div>
  </div>
  <div class="panel">
    <div class="login-card">
      <a class="back" href="/">← Voltar ao portal</a>
      <h1>Entrar</h1>
      <p class="sub">Informe suas credenciais para acessar o sistema do TJD.</p>

      @if ($errors->any())
        <div style="background:#f5dfd8;border:1px solid #f0bbb2;color:#b3261e;border-radius:var(--radius);padding:12px 14px;font-size:.9rem;margin-bottom:16px;">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      @if (session('status'))
        <div class="flash-status">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" @error('email') class="error" @enderror>
          @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" required autocomplete="current-password" @error('password') class="error" @enderror>
          @error('password')<div class="error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="row-between">
          <label style="display:flex;gap:7px;align-items:center;text-transform:none;letter-spacing:0;font-weight:500;color:var(--ink-soft);">
            <input type="checkbox" name="remember" style="width:auto;">
            Manter conectado
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Esqueci a senha</a>
          @endif
        </div>
        <button class="btn btn-primary" type="submit">Acessar o sistema</button>
      </form>
      <p class="note">Acesso destinado a membros do TJD e partes habilitadas.<br>Em caso de dúvidas, contate a Secretaria do TJD.</p>
    </div>
  </div>
</div>
@endsection
