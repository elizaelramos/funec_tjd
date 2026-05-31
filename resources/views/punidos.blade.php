@extends('layouts.app')

@section('title', 'Punidos / Suspensos — TJD · FUNEC')

@section('head')
<style>
.stats{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:28px;}
.stat{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);padding:18px 20px;box-shadow:var(--shadow-sm);}
.stat .n{font-family:var(--serif);font-size:2.1rem;font-weight:700;color:var(--navy-900);line-height:1;}
.stat .l{font-size:.78rem;color:var(--muted);margin-top:8px;letter-spacing:.02em;}
.stat.danger{border-left:4px solid var(--danger);}

.pun-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);
  margin-bottom:16px;overflow:hidden;transition:box-shadow .2s, border-color .2s;}
.pun-card:hover{box-shadow:var(--shadow);border-color:var(--gold-soft);}
.pun-card .head{display:flex;align-items:center;gap:14px;padding:16px 22px;border-bottom:1px solid var(--line-soft);flex-wrap:wrap;}
.pun-card .head .procno{font-size:.9rem;font-weight:600;color:var(--navy-900);}
.pun-card .head .denunciado{font-size:.95rem;color:var(--navy-900);font-weight:600;}
.pun-card .body{padding:18px 22px;}
.pun-card .punicao{font-size:1.05rem;font-weight:600;color:var(--danger);margin-bottom:12px;}
.pun-card .info{display:flex;gap:22px;flex-wrap:wrap;font-size:.85rem;color:var(--muted);margin-bottom:12px;}
.pun-card .info b{color:var(--ink-soft);font-weight:600;}
.prazo-recurso{display:inline-block;background:#e3f2fd;color:#0d47a1;padding:4px 10px;border-radius:var(--radius);font-size:.78rem;font-weight:600;}
.prazo-recurso.em-prazo{background:#fff3cd;color:#856404;}
.prazo-recurso.expirado{background:#f5f5f5;color:#999;}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--muted);}
@media (max-width:760px){ .stats{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Punidos / Suspensos">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Punidos / Suspensos</span></div>
    <h1>Punidos / Suspensos</h1>
    <p class="lede">Relação de atletas e participantes com punição ativa. Atualizada após cada sessão de julgamento. Cabe recurso dentro do prazo indicado.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="stats">
      <div class="stat danger">
        <div class="n">{{ $punidos->count() }}</div>
        <div class="l">Punido{{ $punidos->count() !== 1 ? 's' : '' }}</div>
      </div>
      <div class="stat">
        <div class="n">{{ now()->format('d/m/Y') }}</div>
        <div class="l">Atualizado</div>
      </div>
    </div>

    @if ($punidos->isEmpty())
      <div class="empty">Nenhum punido registrado.</div>
    @else
      @foreach ($punidos as $p)
        @php
          $prazo = $p->prazo_recurso;
        @endphp
        <article class="pun-card">
          <div class="head">
            <span class="procno">Proc. {{ $p->numero }}</span>
            <span class="denunciado">{{ $p->denunciado ?? $p->clube ?? '—' }}</span>
            @if ($prazo['em_prazo'])
              <span class="prazo-recurso em-prazo" style="margin-left:auto;">
                Em prazo de recurso
              </span>
            @else
              <span class="prazo-recurso expirado" style="margin-left:auto;">
                Prazo expirado
              </span>
            @endif
          </div>
          <div class="body">
            <div class="punicao">{{ $p->resultado ?: 'Resultado não informado' }}</div>
            <div class="info">
              <span><b>Competição:</b> {{ $p->competicao }}</span>
              <span><b>Enquadramento:</b> {{ $p->enquadramento ?: '—' }}</span>
              <span><b>Relator:</b> {{ $p->relator ?: '—' }}</span>
            </div>
            @if ($prazo['data_limite'])
              <div class="info">
                @if ($prazo['em_prazo'])
                  <span><b>Prazo de recurso:</b> 72h (até {{ $prazo['data_limite']->format('d/m/Y H:i') }})</span>
                @else
                  <span><b>Prazo de recurso:</b> Expirado (era até {{ $prazo['data_limite']->format('d/m/Y H:i') }})</span>
                @endif
              </div>
            @endif
            <div style="display:flex;gap:10px;margin-top:14px;">
              <a class="btn btn-ghost" href="{{ route('processos.show', $p->numero) }}">Ver processo →</a>
            </div>
          </div>
        </article>
      @endforeach
    @endif
  </div>
</section>
@endsection
