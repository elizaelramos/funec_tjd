@extends('layouts.app')

@section('title', 'Pauta de Julgamentos — TJD · FUNEC')

@section('head')
<style>
.filters{display:flex;gap:12px;flex-wrap:wrap;align-items:center;background:var(--surface);
  border:1px solid var(--line);border-radius:var(--radius-lg);padding:14px 18px;box-shadow:var(--shadow-sm);margin-bottom:30px;}
.filters .fl{display:flex;flex-direction:column;gap:4px;}
.filters label{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);}
.filters select{font-family:var(--sans);font-size:.9rem;padding:8px 12px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface-2);color:var(--ink);min-width:200px;}
.filters .grow{margin-left:auto;}

.session{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:30px;}
.session-top{display:grid;grid-template-columns:auto 1fr auto;gap:22px;align-items:center;padding:22px 26px;background:linear-gradient(180deg,var(--surface),var(--surface-2));border-bottom:1px solid var(--line);}
.session-top .cal{width:78px;height:82px;border-radius:8px;display:flex;flex-direction:column;overflow:hidden;border:1px solid var(--gold);box-shadow:var(--shadow-sm);flex:none;background:var(--surface);}
.session-top .cal .m{background:linear-gradient(180deg,var(--gold-bright),var(--gold));color:var(--navy-950);font-size:.66rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;text-align:center;padding:4px 0;}
.session-top .cal .d{font-family:var(--serif);font-weight:700;font-size:2.1rem;text-align:center;line-height:1.7;color:var(--navy-900);}
.session-top h3{font-size:1.3rem;}
.session-top .meta{color:var(--muted);font-size:.9rem;margin-top:5px;display:flex;gap:16px;flex-wrap:wrap;}
.session-top .meta b{color:var(--ink-soft);font-weight:600;}

table.data td .parts{font-weight:600;color:var(--navy-900);}
table.data td .comp{font-size:.78rem;color:var(--muted);margin-top:2px;}
.tipo{font-size:.72rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
.tipo-den{color:var(--danger);}
.tipo-rec{color:var(--navy-600);}
.session .foot{padding:14px 26px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;background:var(--surface-2);border-top:1px solid var(--line-soft);font-size:.86rem;color:var(--muted);}
@media (max-width:760px){ .session-top{grid-template-columns:auto 1fr;} .session-top .badge{grid-column:1 / -1;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Pauta de Julgamentos">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Pauta de Julgamentos</span></div>
    <h1>Pauta de Julgamentos</h1>
    <p class="lede">Sessões de julgamento agendadas, com data, horário e a relação de processos a serem apreciados pelo Pleno e pela Comissão Disciplinar.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="filters">
      <div class="fl"><label>Órgão julgador</label><select><option>Todos os órgãos</option><option>Comissão Disciplinar</option><option>Pleno do TJD</option></select></div>
      <div class="fl"><label>Competição</label><select><option>Todas as competições</option><option>Campeonato Corumbaense — Série A</option><option>Copa FUNEC de Futsal</option><option>Campeonato Municipal Sub-20</option><option>Citadino de Society</option></select></div>
      <div class="fl"><label>Situação</label><select><option>Todas</option><option>Agendada</option><option>Julgada</option></select></div>
      <a class="btn btn-primary grow" href="#">Filtrar</a>
    </div>

    @forelse ($pautas as $pauta)
      <article class="session">
        <div class="session-top">
          <div class="cal" @if($pauta->situacao === 'julgada') style="border-color:var(--line);" @endif>
            <span class="m" @if($pauta->situacao === 'julgada') style="background:var(--navy-800);color:#fff;" @endif>{{ $pauta->data->format('M') }}</span>
            <span class="d">{{ $pauta->data->format('d') }}</span>
          </div>
          <div>
            <h3>{{ $pauta->titulo }}</h3>
            <div class="meta">
              <span><b>{{ $pauta->data->format('l') }}</b>, {{ $pauta->data->format('d/m/Y') }}</span>
              <span><b>{{ $pauta->hora }}</b></span>
              @if ($pauta->local)
                <span>{{ $pauta->local }}</span>
              @endif
            </div>
          </div>
          @if ($pauta->situacao === 'julgada')
            <span class="badge badge-julgada"><span class="tick"></span>Julgada</span>
          @else
            <span class="badge badge-agendada"><span class="tick"></span>Agendada</span>
          @endif
        </div>
        @if ($pauta->processos->isNotEmpty())
          <div class="table-wrap" style="border:0;border-radius:0;">
            <table class="data">
              <thead>
                <tr>
                  <th>Processo</th>
                  <th>Partes / Competição</th>
                  <th>Relator</th>
                  @if ($pauta->situacao === 'julgada')
                    <th>Resultado</th>
                  @else
                    <th>Enquadramento</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach ($pauta->processos as $processo)
                  <tr>
                    <td><span class="procno">{{ $processo->numero }}</span></td>
                    <td>
                      <div class="parts">{{ $processo->denunciado ?? '—' }}</div>
                    </td>
                    <td>{{ $processo->relator ?? '—' }}</td>
                    <td>
                      @if ($pauta->situacao === 'julgada')
                        {{ $processo->resultado ?? '—' }}
                      @else
                        @if ($processo->enquadramento)
                          <span class="chip">{{ $processo->enquadramento }}</span>
                        @else
                          —
                        @endif
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="foot">
            <span><b>{{ $pauta->processos->count() }} processo{{ $pauta->processos->count() !== 1 ? 's' : '' }}</b>
            @if ($pauta->situacao === 'julgada')
              julgado{{ $pauta->processos->count() !== 1 ? 's' : '' }}
            @else
              em pauta
            @endif
            · Sessão pública</span>
            <a class="btn btn-ghost" href="/processos">Detalhar processos →</a>
          </div>
        @else
          <div style="padding:20px 26px;text-align:center;color:var(--muted);">
            Nenhum processo agendado para esta sessão.
          </div>
        @endif
      </article>
    @empty
      <div class="empty" style="text-align:center;">
        <p>Nenhuma sessão de julgamento agendada no momento.</p>
      </div>
    @endforelse
  </div>
</section>
@endsection
