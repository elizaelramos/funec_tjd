@extends('layouts.app')

@section('title', 'Gestão de Pautas — TJD · FUNEC')

@section('head')
<style>
.admin-bar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:22px;}
.flash{background:#e8f3ec;border:1px solid #c5e2d0;color:#2f7d51;border-radius:var(--radius);padding:12px 18px;margin-bottom:22px;font-size:.92rem;font-weight:600;}
.actions{display:flex;gap:8px;}
.actions form{display:inline;}
.btn-sm{padding:6px 12px;font-size:.82rem;}
.btn-danger{background:var(--danger);color:#fff;border:1px solid var(--danger);}
.btn-danger:hover{filter:brightness(.93);}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--muted);}
table.data td.num{font-family:var(--mono);font-weight:600;color:var(--navy-900);}
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Gestão de Pautas">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Gestão de Pautas</span></div>
    <h1>Gestão de Pautas de Julgamento</h1>
    <p class="lede">Área restrita — agendamento, edição e exclusão de sessões de julgamento.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if (session('ok'))
      <div class="flash">{{ session('ok') }}</div>
    @endif

    <div class="admin-bar">
      <span class="eyebrow">{{ $pautas->count() }} pauta(s) cadastrada(s)</span>
      <div style="display:flex;gap:12px;">
        <a class="btn btn-primary" href="{{ route('admin.pautas.create') }}">+ Nova pauta</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
      </div>
    </div>

    @if ($pautas->isEmpty())
      <div class="empty">Nenhuma pauta cadastrada. Clique em "Nova pauta" para agendar uma sessão de julgamento.</div>
    @else
      <div class="table-wrap">
        <table class="data">
          <thead><tr><th>Pauta</th><th>Órgão Julgador</th><th>Data</th><th>Hora</th><th>Processos</th><th>Situação</th><th style="text-align:right;">Ações</th></tr></thead>
          <tbody>
            @foreach ($pautas as $pauta)
              <tr>
                <td class="num">{{ $pauta->numero }}</td>
                <td>{{ \App\Http\Controllers\PautaController::ORGAOS[$pauta->orgao_julgador] ?? $pauta->orgao_julgador }}</td>
                <td>{{ $pauta->data->format('d/m/Y') }}</td>
                <td>{{ $pauta->hora }}</td>
                <td>{{ $pauta->processos->count() }}</td>
                <td>
                  @if ($pauta->situacao === 'julgada')
                    <span class="badge badge-julgada"><span class="tick"></span>Julgada</span>
                  @elseif ($pauta->situacao === 'agendada')
                    <span class="badge badge-agendada"><span class="tick"></span>Agendada</span>
                  @else
                    <span class="badge">{{ \App\Http\Controllers\PautaController::SITUACOES[$pauta->situacao] ?? $pauta->situacao }}</span>
                  @endif
                </td>
                <td>
                  <div class="actions" style="justify-content:flex-end;">
                    @can('editor')
                      @if ($pauta->situacao === 'agendada' && $pauta->processos->isNotEmpty())
                        <a class="btn btn-sm" href="{{ route('admin.pautas.resultados', $pauta) }}" style="background:#4caf50;color:white;border:1px solid #4caf50;">Resultados</a>
                      @endif
                      <a class="btn btn-ghost btn-sm" href="{{ route('admin.pautas.edit', $pauta) }}">Editar</a>
                      <form method="POST" action="{{ route('admin.pautas.destroy', $pauta) }}"
                            onsubmit="return confirm('Excluir a pauta {{ $pauta->numero }}? Esta ação não pode ser desfeita.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                      </form>
                    @endcan
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
