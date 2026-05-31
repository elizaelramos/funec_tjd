@extends('layouts.app')

@section('title', 'Consulta de Processos — TJD · FUNEC')

@section('head')
<style>
.consult{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:26px;margin-bottom:34px;}
.consult h2{font-size:1.25rem;}
.consult .form{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;}
.consult .field{flex:1;min-width:260px;display:flex;align-items:center;gap:10px;border:1px solid var(--line);background:var(--surface-2);border-radius:var(--radius);padding:12px 16px;}
.consult .field input{border:0;background:transparent;outline:none;font-family:inherit;font-size:1rem;width:100%;color:var(--ink);}
.consult .hint{margin-top:12px;font-size:.82rem;color:var(--muted);}
.consult .hint a{color:var(--navy-700);font-weight:600;}
.res-count{font-size:.85rem;color:var(--muted);margin-bottom:14px;}
.res-count b{color:var(--ink);}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--muted);}
table.data td .parts{font-weight:600;color:var(--navy-900);}
table.data td .comp{font-size:.78rem;color:var(--muted);margin-top:2px;}
table.data tr.clickable{cursor:pointer;}
table.data tr.clickable:hover{background:var(--surface-2);}
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Consulta de Processos">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Consulta de Processos</span></div>
    <h1>Consulta de Processos</h1>
    <p class="lede">Acompanhe a tramitação de um processo disciplinar por número, clube ou atleta — desde a denúncia até a decisão final.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    {{-- Formulário de busca: envia ?q= por GET para esta mesma rota --}}
    <form class="consult" method="GET" action="{{ route('processos.index') }}">
      <h2>Buscar processo</h2>
      <div class="form">
        <div class="field">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--navy-700)" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
          <input type="text" name="q" value="{{ $q }}" placeholder="Número, atleta, clube ou competição…" aria-label="Buscar processo">
        </div>
        <button class="btn btn-primary" type="submit">Consultar</button>
      </div>
      <p class="hint">Formato do número: <b>NNN/AAAA</b>. Também é possível buscar por nome de atleta, clube ou competição. <a href="/pauta">Ver pauta completa →</a></p>
    </form>

    {{-- RESULTADOS (vindos do banco) --}}
    @if ($q !== '')
      <p class="res-count"><b>{{ $processos->count() }}</b> resultado(s) para “{{ $q }}”.</p>
    @else
      <p class="res-count">Exibindo os <b>{{ $processos->count() }}</b> processos mais recentes.</p>
    @endif

    @if ($processos->isEmpty())
      <div class="empty">Nenhum processo encontrado. Tente outro termo de busca.</div>
    @else
      <div class="table-wrap">
        <table class="data">
          <thead><tr><th>Processo</th><th>Tipo</th><th>Partes / Competição</th><th>Situação</th><th>Resultado</th></tr></thead>
          <tbody>
            @foreach ($processos as $p)
              <tr class="clickable" onclick="window.location='{{ route('processos.show', $p->numero) }}'">
                <td><span class="procno">{{ $p->numero }}</span></td>
                <td>
                  <span class="tipo {{ $p->tipo === 'recurso' ? 'tipo-rec' : 'tipo-den' }}">
                    {{ $p->tipo === 'recurso' ? 'Recurso' : 'Denúncia' }}
                  </span>
                </td>
                <td>
                  <div class="parts">{{ $p->partida ?: ($p->denunciado ?: $p->clube ?: '—') }}</div>
                  <div class="comp">{{ $p->competicao }}</div>
                </td>
                <td>
                  @if ($p->situacao === 'julgado')
                    <span class="badge badge-julgada"><span class="tick"></span>Julgado</span>
                  @elseif ($p->situacao === 'agendado')
                    <span class="badge badge-agendada"><span class="tick"></span>Agendado</span>
                  @else
                    <span class="badge">Em tramitação</span>
                  @endif
                </td>
                <td>{{ $p->resultado ?: '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>
@endsection
