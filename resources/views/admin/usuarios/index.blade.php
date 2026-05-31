@extends('layouts.app')

@section('title', 'Gestão de Usuários — TJD · FUNEC')

@section('head')
<style>
.admin-bar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:22px;}
.flash{background:#e8f3ec;border:1px solid #c5e2d0;color:#2f7d51;border-radius:var(--radius);padding:12px 18px;margin-bottom:22px;font-size:.92rem;font-weight:600;}
.flash.erro{background:#fdecea;border-color:#f0bbb2;color:#b3261e;}
.actions{display:flex;gap:8px;}
.actions form{display:inline;}
.btn-sm{padding:6px 12px;font-size:.82rem;}
.btn-danger{background:var(--danger);color:#fff;border:1px solid var(--danger);}
.btn-danger:hover{filter:brightness(.93);}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--muted);}
table.data td.cargo{font-weight:600;color:var(--navy-700);}
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Gestão de Usuários">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Gestão de Usuários</span></div>
    <h1>Gestão de Usuários</h1>
    <p class="lede">Área restrita — cadastro, edição e exclusão de membros do TJD (Secretaria, Procurador, Auditor).</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if (session('ok'))
      <div class="flash">{{ session('ok') }}</div>
    @endif

    @if (session('erro'))
      <div class="flash erro">{{ session('erro') }}</div>
    @endif

    <div class="admin-bar">
      <span class="eyebrow">{{ $usuarios->count() }} usuário(s) cadastrado(s)</span>
      <div style="display:flex;gap:12px;">
        <a class="btn btn-primary" href="{{ route('admin.usuarios.create') }}">+ Novo usuário</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
      </div>
    </div>

    @if ($usuarios->isEmpty())
      <div class="empty">Nenhum usuário cadastrado ainda. Clique em "Novo usuário" para começar.</div>
    @else
      <div class="table-wrap">
        <table class="data">
          <thead><tr><th>Nome</th><th>E-mail</th><th>Cargo</th><th>Nível</th><th>Cadastrado em</th><th style="text-align:right;">Ações</th></tr></thead>
          <tbody>
            @foreach ($usuarios as $u)
              <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td class="cargo">{{ \App\Http\Controllers\UsuarioController::CARGOS[$u->cargo] ?? '—' }}</td>
                <td class="cargo">{{ \App\Http\Controllers\UsuarioController::NIVEIS[$u->level] ?? '—' }}</td>
                <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                <td>
                  <div class="actions" style="justify-content:flex-end;">
                    <a class="btn btn-ghost btn-sm" href="{{ route('admin.usuarios.edit', $u) }}">Editar</a>
                    <form method="POST" action="{{ route('admin.usuarios.destroy', $u) }}"
                          onsubmit="return confirm('Excluir o usuário {{ $u->name }}? Esta ação não pode ser desfeita.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>
@endsection
