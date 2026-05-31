@extends('layouts.app')

@php
use Carbon\Carbon;
$citacoes = \App\Models\Documento::where('tipo', 'citacao')
    ->with('processo')
    ->orderByDesc('created_at')
    ->get();
@endphp

@section('title', 'Citações — TJD · FUNEC')

@section('head')
<style>
.howto{display:flex;gap:14px;align-items:flex-start;background:#eaf0fa;border:1px solid #cdddf4;border-radius:var(--radius-lg);padding:18px 22px;margin-bottom:28px;}
.howto .ic{width:38px;height:38px;border-radius:9px;background:var(--navy-800);color:var(--gold-bright);display:flex;align-items:center;justify-content:center;flex:none;}
.howto p{font-size:.92rem;color:var(--ink-soft);line-height:1.6;}
.howto b{color:var(--navy-900);}

.cit{display:grid;grid-template-columns:auto 1fr auto;gap:22px;align-items:center;background:var(--surface);
  border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:20px 24px;margin-bottom:14px;
  transition:box-shadow .2s, border-color .2s;}
.cit:hover{box-shadow:var(--shadow);border-color:var(--gold-soft);}
.cit .doc{width:48px;height:56px;border-radius:6px;background:var(--surface-2);border:1px solid var(--line);position:relative;flex:none;display:flex;align-items:flex-end;justify-content:center;padding-bottom:8px;}
.cit .doc::before{content:"";position:absolute;top:0;right:0;width:16px;height:16px;background:var(--paper-2);border-left:1px solid var(--line);border-bottom:1px solid var(--line);}
.cit .doc span{font-family:var(--mono);font-size:.58rem;font-weight:600;color:var(--navy-700);letter-spacing:.04em;}
.cit .info h4{font-size:1.08rem;font-weight:600;}
.cit .info .sub{color:var(--muted);font-size:.86rem;margin-top:3px;}
.cit .info .deadline{margin-top:9px;font-size:.84rem;}
.cit .info .deadline b{color:var(--ink-soft);}
.cit .actions{display:flex;flex-direction:column;gap:8px;align-items:flex-end;}
.prazo{font-family:var(--mono);font-size:.8rem;font-weight:600;}
.prazo.open{color:var(--warn);}
.prazo.closed{color:var(--muted);}
.empty-cit{text-align:center;padding:40px 20px;color:var(--muted);}

.cit .actions{display:flex;gap:8px;align-items:center;}

.modal-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:9999;padding:20px;}
.modal-overlay.hidden{display:none;}
.modal-content{background:white;border-radius:var(--radius-lg);width:95vw;max-width:1400px;max-height:90vh;overflow:auto;position:relative;box-shadow:0 20px 60px rgba(0,0,0,.3);}
.modal-close{position:absolute;top:12px;right:12px;width:32px;height:32px;background:var(--navy-900);color:white;border:none;border-radius:50%;cursor:pointer;font-size:1.2rem;display:flex;align-items:center;justify-content:center;z-index:10000;}
.modal-close:hover{opacity:.8;}
.modal-pdf{width:100%;height:80vh;}
.modal-image{max-width:100%;max-height:85vh;display:block;margin:0 auto;}

@media (max-width:680px){ .cit{grid-template-columns:auto 1fr;} .cit .actions{grid-column:1/-1;flex-direction:row;align-items:center;justify-content:space-between;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Citações">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Citações</span></div>
    <h1>Citações</h1>
    <p class="lede">Documentos de citação publicados automaticamente após a emissão pela Procuradoria. A publicação dá início à contagem do prazo de defesa do denunciado.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="howto">
      <span class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
      <p>Emitida a denúncia, a citação é <b>publicada automaticamente</b> neste portal. O denunciado deverá apresentar sua defesa na <b>sessão de julgamento</b> agendada, pessoalmente ou pelo <a href="/login">acesso restrito</a>.</p>
    </div>

    @if ($citacoes->isEmpty())
    <div class="empty-cit">
      <p>Nenhuma citação publicada no momento.</p>
    </div>
    @else
      @foreach ($citacoes as $cit)
      @php
        $dataPublicacao = $cit->created_at;
      @endphp
      <div class="cit">
        <div class="doc"><span>PDF</span></div>
        <div class="info">
          <h4>{{ $cit->titulo }}{{ $cit->processo ? ' — ' . $cit->processo->denunciado : '' }}</h4>
          <div class="sub">
            <span class="procno">Proc. {{ $cit->processo->numero }}</span>
            @if ($cit->processo->enquadramento) · {{ $cit->processo->enquadramento }} @endif
            · {{ $cit->processo->competicao }}
          </div>
          <div class="deadline">
            <b>Publicada:</b> {{ $dataPublicacao->format('d/m/Y') }}
          </div>
        </div>
        <div class="actions">
          @if ($cit->arquivo)
            <button class="btn btn-gold" onclick="abrirVisualizador('{{ route('documentos.view', $cit) }}', 'pdf', '{{ addslashes($cit->titulo) }}')">
              Visualizar
            </button>
            <a class="btn btn-gold" href="{{ route('documentos.download', $cit) }}" download>
              Baixar citação
            </a>
          @endif
        </div>
      </div>
      @endforeach

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
</section>
@endsection
