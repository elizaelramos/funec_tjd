@extends('layouts.app')

@section('title', 'Decisões e Acórdãos — TJD · FUNEC')

@section('head')
<style>
.searchbar{display:flex;gap:12px;flex-wrap:wrap;align-items:center;background:var(--surface);border:1px solid var(--line);
  border-radius:var(--radius-lg);padding:14px 16px;box-shadow:var(--shadow-sm);margin-bottom:14px;}
.searchbar .field{flex:1;min-width:240px;display:flex;align-items:center;gap:10px;border:1px solid var(--line);background:var(--surface-2);border-radius:var(--radius);padding:10px 14px;}
.searchbar .field input{border:0;background:transparent;outline:none;font-family:inherit;font-size:.95rem;width:100%;color:var(--ink);}
.searchbar select{font-family:var(--sans);font-size:.9rem;padding:10px 12px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface-2);color:var(--ink);}
.result-count{font-size:.86rem;color:var(--muted);margin-bottom:22px;}
.result-count b{color:var(--ink);}

.dec{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);
  margin-bottom:16px;overflow:hidden;transition:box-shadow .2s, border-color .2s;}
.dec:hover{box-shadow:var(--shadow);border-color:var(--gold-soft);}
.dec .head{display:flex;align-items:center;gap:14px;padding:16px 22px;border-bottom:1px solid var(--line-soft);flex-wrap:wrap;}
.dec .head .procno{font-size:.9rem;}
.dec .head .dt{font-size:.82rem;color:var(--muted);margin-left:auto;}
.dec .body{padding:18px 22px;}
.dec .ementa{font-family:var(--serif);font-size:1.18rem;font-weight:600;color:var(--navy-900);line-height:1.3;}
.dec .meta{display:flex;gap:22px;flex-wrap:wrap;margin-top:12px;font-size:.86rem;color:var(--muted);}
.dec .meta b{color:var(--ink-soft);font-weight:600;}
.dec .foot{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:18px;}
.outcome{display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:.9rem;}
.outcome.pun{color:var(--danger);}
.outcome.abs{color:var(--ok);}
.outcome .ic{width:24px;height:24px;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;}
.outcome.pun .ic{background:var(--danger);}
.outcome.abs .ic{background:var(--ok);}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--muted);}
.prazo-recurso{display:inline-block;background:#e3f2fd;color:#0d47a1;padding:4px 10px;border-radius:var(--radius);font-size:.78rem;font-weight:600;margin-left:8px;}
.prazo-recurso.em-prazo{background:#fff3cd;color:#856404;}
.prazo-recurso.expirado{background:#f5f5f5;color:#999;}
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Decisões">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Decisões e Acórdãos</span></div>
    <h1>Decisões e Acórdãos</h1>
    <p class="lede">Resultado dos julgamentos do TJD — punições aplicadas e absolvições. Consulte por processo, clube, atleta ou competição.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <p class="result-count">Exibindo <b>{{ $decisoes->count() }}</b> {{ $decisoes->count() == 1 ? 'decisão' : 'decisões' }} publicada(s).</p>

    @forelse ($decisoes as $d)
      @php
        $absolvido = str_contains(mb_strtolower($d->resultado ?? ''), 'absolv');
        $prazo = $d->prazo_recurso;
      @endphp
      <article class="dec">
        <div class="head">
          <span class="procno">Proc. {{ $d->numero }}</span>
          <span class="badge badge-julgada"><span class="tick"></span>Julgado</span>
          @if ($d->enquadramento)<span class="chip">{{ $d->enquadramento }}</span>@endif
          <span class="dt">Julgado em {{ optional($d->data_julgamento)->format('d/m/Y') ?: '—' }}</span>
        </div>
        <div class="body">
          <div class="ementa">{{ $d->assunto }}</div>
          <div class="meta">
            <span><b>Partes:</b> {{ $d->partida ?: ($d->denunciado ?: $d->clube ?: '—') }}</span>
            <span><b>Competição:</b> {{ $d->competicao }}</span>
            <span><b>Relator:</b> {{ $d->relator ?: '—' }}</span>
          </div>
          @if ($prazo['data_limite'])
            <div class="meta" style="margin-top: 8px;">
              @if ($prazo['em_prazo'])
                <span class="prazo-recurso em-prazo">
                  Prazo de recurso: 72h (até {{ $prazo['data_limite']->format('d/m/Y H:i') }})
                </span>
              @else
                <span class="prazo-recurso expirado">
                  Prazo de recurso expirado (era até {{ $prazo['data_limite']->format('d/m/Y H:i') }})
                </span>
              @endif
            </div>
          @endif
          <div class="foot">
            @if ($absolvido)
              <span class="outcome abs">
                <span class="ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>
                {{ $d->resultado }}
              </span>
            @else
              <span class="outcome pun">
                <span class="ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
                {{ $d->resultado ?: 'Resultado não informado' }}
              </span>
            @endif
            <a class="btn btn-ghost" style="margin-left:auto;" href="{{ route('processos.show', $d->numero) }}">Ver processo →</a>
          </div>
        </div>
      </article>
    @empty
      <div class="empty">Ainda não há decisões publicadas.</div>
    @endforelse
  </div>
</section>
@endsection
