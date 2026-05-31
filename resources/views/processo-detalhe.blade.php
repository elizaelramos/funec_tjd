@extends('layouts.app')

@section('title', 'Processo nº ' . $processo->numero . ' — TJD · FUNEC')

@section('head')
<style>
.proc-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;
  background:var(--navy-900);color:#fff;border-radius:var(--radius-lg) var(--radius-lg) 0 0;padding:24px 28px;}
.proc-head .pn{font-family:var(--mono);font-size:.9rem;color:var(--gold-bright);letter-spacing:.04em;}
.proc-head h2{color:#fff;font-size:1.5rem;margin-top:6px;max-width:40ch;}
.proc-body{background:var(--surface);border:1px solid var(--line);border-top:0;border-radius:0 0 var(--radius-lg) var(--radius-lg);box-shadow:var(--shadow-sm);}
.proc-meta{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border-bottom:1px solid var(--line);}
.proc-meta .m{background:var(--surface);padding:16px 22px;}
.proc-meta .m .k{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);}
.proc-meta .m .v{font-size:.95rem;font-weight:600;color:var(--navy-900);margin-top:5px;}

.tram{padding:28px;}
.tram h3{font-size:1.15rem;margin-bottom:20px;}
.timeline{position:relative;padding-left:34px;}
.timeline::before{content:"";position:absolute;left:11px;top:6px;bottom:6px;width:2px;background:var(--line);}
.step{position:relative;padding-bottom:24px;}
.step:last-child{padding-bottom:0;}
.step .dot{position:absolute;left:-34px;top:1px;width:24px;height:24px;border-radius:50%;background:var(--surface);border:2px solid var(--line);display:flex;align-items:center;justify-content:center;}
.step.done .dot{background:var(--ok);border-color:var(--ok);color:#fff;}
.step.current .dot{background:var(--gold);border-color:var(--gold-deep);color:var(--navy-950);box-shadow:0 0 0 4px rgba(195,154,63,.22);}
.step .t{font-weight:600;color:var(--navy-900);font-size:1rem;}
.step .d{font-family:var(--mono);font-size:.78rem;color:var(--muted);margin-top:2px;}
.step .x{font-size:.88rem;color:var(--ink-soft);margin-top:6px;line-height:1.55;}
.no-tram{color:var(--muted);font-size:.9rem;}

.docs-section{padding:28px;}
.docs-section h3{font-size:1.15rem;margin-bottom:20px;}
.doc-group{margin-bottom:24px;}
.doc-group-title{font-size:.85rem;font-weight:700;text-transform:uppercase;color:var(--muted);letter-spacing:.05em;margin-bottom:12px;}
.doc-list{list-style:none;padding:0;margin:0;}
.doc-list li{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--surface-2);border-radius:var(--radius);margin-bottom:10px;gap:12px;}
.doc-list li .info{flex:1;}
.doc-list li .title{font-weight:600;color:var(--ink);display:block;margin-bottom:2px;}
.doc-list li .meta{font-size:.75rem;color:var(--muted);}
.doc-list li .actions{display:flex;gap:8px;align-items:center;}
.doc-list li a{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gold);color:var(--navy-900);border-radius:var(--radius);text-decoration:none;font-weight:600;font-size:.8rem;transition:opacity .2s;cursor:pointer;border:none;}
.doc-list li a:hover{opacity:.9;}
.doc-empty{color:var(--muted);font-size:.9rem;text-align:center;padding:18px;}

.modal-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:9999;padding:20px;}
.modal-overlay.hidden{display:none;}
.modal-content{background:white;border-radius:var(--radius-lg);width:95vw;max-width:1400px;max-height:90vh;overflow:auto;position:relative;box-shadow:0 20px 60px rgba(0,0,0,.3);}
.modal-close{position:absolute;top:12px;right:12px;width:32px;height:32px;background:var(--navy-900);color:white;border:none;border-radius:50%;cursor:pointer;font-size:1.2rem;display:flex;align-items:center;justify-content:center;z-index:10000;}
.modal-close:hover{opacity:.8;}
.modal-pdf{width:100%;height:80vh;}
.modal-image{max-width:100%;max-height:85vh;display:block;margin:0 auto;}

@media (max-width:760px){ .proc-meta{grid-template-columns:repeat(2,1fr);} .doc-list li .actions{flex-direction:column;align-items:flex-start;} .modal-content{max-height:80vh;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Detalhe do Processo">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><a href="{{ route('processos.index') }}">Processos</a><span class="sep">/</span><span>{{ $processo->numero }}</span></div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="proc-head">
      <div>
        <span class="pn">Processo nº {{ $processo->numero }}</span>
        <h2>{{ $processo->assunto }}</h2>
      </div>
      @if ($processo->situacao === 'julgado')
        <span class="badge badge-julgada"><span class="tick"></span>Julgado</span>
      @elseif ($processo->situacao === 'agendado')
        <span class="badge badge-agendada"><span class="tick"></span>Agendado</span>
      @else
        <span class="badge">Em tramitação</span>
      @endif
    </div>
    <div class="proc-body">
      <div class="proc-meta">
        <div class="m"><div class="k">Órgão julgador</div><div class="v">{{ $processo->orgao_julgador }}</div></div>
        <div class="m"><div class="k">Competição</div><div class="v">{{ $processo->competicao }}</div></div>
        <div class="m"><div class="k">Relator</div><div class="v">{{ $processo->relator ?: '—' }}</div></div>
        <div class="m"><div class="k">Enquadramento</div><div class="v">{{ $processo->enquadramento ?: '—' }}</div></div>
        <div class="m"><div class="k">Denunciante</div><div class="v">{{ $processo->denunciante ?: '—' }}</div></div>
        <div class="m"><div class="k">Denunciado</div><div class="v">{{ $processo->denunciado ?: '—' }}</div></div>
        <div class="m"><div class="k">Partida</div><div class="v">{{ $processo->partida ?: '—' }}</div></div>
        <div class="m"><div class="k">Resultado</div><div class="v" @if($processo->resultado && $processo->situacao==='julgado') style="color:var(--danger);" @endif>{{ $processo->resultado ?: '—' }}</div></div>
      </div>
      <div class="tram">
        <h3>Tramitação do processo</h3>
        @if ($processo->andamentos->isEmpty())
          <p class="no-tram">Ainda não há andamentos registrados para este processo.</p>
        @else
          <div class="timeline">
            @foreach ($processo->andamentos as $a)
              <div class="step {{ $a->status }}">
                <span class="dot">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg>
                </span>
                <div class="t">{{ $a->titulo }}</div>
                @if ($a->data)
                  <div class="d">{{ $a->data->format('d/m/Y · H\hi') }}</div>
                @endif
                @if ($a->descricao)
                  <div class="x">{{ $a->descricao }}</div>
                @endif
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Documentos --}}
      @php
        $documentos = $processo->documentos->groupBy('tipo');

        // Adicionar ata de julgamento da pauta julgada
        $atasJulgamento = collect();
        foreach ($processo->pautas as $pauta) {
          if ($pauta->situacao === 'julgada') {
            $atasJulgamento = $atasJulgamento->merge($pauta->documentos->where('tipo', 'ata'));
          }
        }
        if ($atasJulgamento->count() > 0) {
          $documentos['ata'] = $atasJulgamento;
        }

        $tiposDoc = [
          'origem' => 'Origem do Processo',
          'citacao' => 'Citação',
          'ata' => 'Ata de Julgamento',
          'recurso' => 'Recurso',
          'decisao_recurso' => 'Decisão do Recurso'
        ];
      @endphp

      @if ($documentos->count() > 0)
      <div class="docs-section">
        <h3>Documentos</h3>
        @foreach ($tiposDoc as $tipo => $rotulo)
          @if ($documentos->has($tipo) && $documentos[$tipo]->whereNotNull('arquivo')->count() > 0)
          <div class="doc-group">
            <div class="doc-group-title">{{ $rotulo }}</div>
            <ul class="doc-list">
              @foreach ($documentos[$tipo]->whereNotNull('arquivo') as $doc)
              <li>
                <div class="info">
                  <span class="title">{{ $doc->titulo }}</span>
                  <span class="meta">
                    @if ($doc->data) {{ $doc->data->format('d/m/Y') }} · @endif
                    {{ $doc->nome_original }}
                  </span>
                </div>
                <div class="actions">
                  @php
                    $ext = strtolower(pathinfo($doc->nome_original, PATHINFO_EXTENSION));
                    $podeVisualizar = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif']);
                  @endphp
                  @if ($podeVisualizar)
                    <button class="btn btn-gold" onclick="abrirVisualizador('{{ route('documentos.view', $doc) }}', '{{ $ext }}', '{{ addslashes($doc->titulo) }}')">
                      Visualizar
                    </button>
                  @endif
                  <a href="{{ route('documentos.download', $doc) }}" class="btn btn-gold" download>
                    Baixar
                  </a>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
          @endif
        @endforeach
      </div>

      {{-- Modal de Visualização --}}
      <div class="modal-overlay hidden" id="modalVisualizador">
        <div class="modal-content">
          <button class="modal-close" onclick="fecharVisualizador()">✕</button>
          <div id="modalBody"></div>
        </div>
      </div>

      <script>
      function abrirVisualizador(url, tipo, titulo) {
        const modal = document.getElementById('modalVisualizador');
        const body = document.getElementById('modalBody');

        if (tipo === 'pdf') {
          body.innerHTML = `<iframe src="${url}#view=FitH" class="modal-pdf"></iframe>`;
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(tipo)) {
          body.innerHTML = `<img src="${url}" class="modal-image" alt="${titulo}">`;
        }

        modal.classList.remove('hidden');
      }

      function fecharVisualizador() {
        document.getElementById('modalVisualizador').classList.add('hidden');
        document.getElementById('modalBody').innerHTML = '';
      }

      // Fechar modal ao clicar fora
      document.getElementById('modalVisualizador')?.addEventListener('click', (e) => {
        if (e.target.id === 'modalVisualizador') {
          fecharVisualizador();
        }
      });
      </script>
      @endif
    </div>
  </div>
</section>
@endsection
