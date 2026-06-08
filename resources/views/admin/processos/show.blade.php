@extends('layouts.admin')

@section('title', 'Processo ' . $processo->numero . ' — TJD · FUNEC')

@section('head')
<style>
.page-section { margin-bottom: 32px; }
.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 2px solid var(--gold); padding-bottom: 12px; }
.section-head h2 { font-size: 1.3rem; margin: 0; color: var(--navy-900); }
.info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-bottom: 20px; }
.info-item { background: var(--surface); padding: 14px; border-radius: var(--radius); border-left: 3px solid var(--gold); }
.info-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: 0.08em; margin-bottom: 4px; }
.info-value { font-size: 0.95rem; color: var(--ink); font-weight: 600; }
.badge { display: inline-block; padding: 6px 12px; border-radius: var(--radius); font-size: 0.8rem; font-weight: 600; }
.badge-aguardando_citacao { background: #fff3e0; color: #e65100; }
.badge-aguardando_agendamento { background: #f3e5f5; color: #4a148c; }
.badge-agendado { background: #e3f2fd; color: #0d47a1; }
.badge-julgado_periodo_recurso { background: #fff8e1; color: #f57f17; }
.badge-recurso_aceito { background: #e8f5e9; color: #2e7d32; }
.badge-julgado { background: #e8f5e9; color: #1b5e20; }
.badge-arquivado { background: #eceff1; color: #37474f; }

.form-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 20px; margin-bottom: 18px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.field label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
.field label .req { color: var(--danger); }
.field input, .field select, .field textarea { font-family: inherit; font-size: 0.95rem; padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface-2); color: var(--ink); width: 100%; }
.field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(195,154,63,.15); }
.field textarea { resize: vertical; min-height: 100px; }
.doc-list { list-style: none; padding: 0; margin: 0; }
.doc-item { background: var(--surface-2); padding: 14px; border-radius: var(--radius); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
.doc-info { flex: 1; }
.doc-title { font-weight: 600; color: var(--ink); margin-bottom: 4px; }
.doc-meta { font-size: 0.8rem; color: var(--muted); }
.doc-actions { display: flex; gap: 8px; }
.btn-small { padding: 6px 12px; font-size: 0.8rem; border: none; border-radius: var(--radius); cursor: pointer; }
.btn-download { background: var(--gold); color: var(--navy-900); }
.btn-delete { background: var(--danger); color: white; }
.btn-download:hover { opacity: 0.9; }
.btn-delete:hover { opacity: 0.9; }
.btn-primary { background: var(--navy-900); color: white; padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-secondary { background: var(--line); color: var(--ink); padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-primary:hover, .btn-secondary:hover { opacity: 0.9; }
.form-actions { display: flex; gap: 12px; margin-top: 18px; }
.empty-state { background: var(--surface); padding: 20px; border-radius: var(--radius); color: var(--muted); text-align: center; }

/* Mensagens (flash / erros) */
.flash { padding: 12px 16px; border-radius: var(--radius); margin-bottom: 18px; font-size: 0.9rem; font-weight: 600; }
.flash-ok  { background: #e8f5e9; color: #1b5e20; border-left: 4px solid #2e7d32; }
.flash-err { background: #fdecea; color: #b3261e; border-left: 4px solid #b3261e; }
.flash-err ul { margin: 6px 0 0; padding-left: 20px; }

/* Barra de progresso por etapas */
.progresso { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 24px 28px; margin-bottom: 24px; }
.progresso-topo { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 22px; }
.progresso-topo .titulo { font-size: 0.95rem; font-weight: 700; color: var(--navy-900); }
.progresso-topo .pct { font-size: 1.6rem; font-weight: 800; color: var(--gold); line-height: 1; }
.passos { display: flex; position: relative; }
.passos::before { content: ''; position: absolute; top: 16px; left: 7%; right: 7%; height: 3px; background: var(--line); z-index: 0; }
.passo { flex: 1; position: relative; z-index: 1; text-align: center; cursor: pointer; background: none; border: none; font: inherit; padding: 0; }
.passo .bola { width: 34px; height: 34px; margin: 0 auto 8px; border-radius: 50%; background: var(--surface-2); border: 3px solid var(--line); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: var(--muted); transition: all .2s; }
.passo .rotulo { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
.passo.concluido .bola { background: var(--gold); border-color: var(--gold); color: var(--navy-900); }
.passo.concluido .rotulo { color: var(--ink); }
.passo.atual .bola { background: var(--navy-900); border-color: var(--navy-900); color: #fff; box-shadow: 0 0 0 4px rgba(195,154,63,.2); }
.passo.atual .rotulo { color: var(--navy-900); font-weight: 700; }
.passo.aberta .rotulo { text-decoration: underline; text-underline-offset: 4px; }

/* Abas */
.abas-nav { display: flex; gap: 4px; border-bottom: 2px solid var(--line); margin-bottom: 24px; flex-wrap: wrap; }
.aba-btn { background: none; border: none; font: inherit; font-weight: 600; font-size: 0.92rem; color: var(--muted); padding: 12px 18px; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; display: flex; align-items: center; gap: 8px; }
.aba-btn:hover { color: var(--ink); }
.aba-btn.ativa { color: var(--navy-900); border-bottom-color: var(--gold); }
.aba-btn .check { color: #2e7d32; font-weight: 800; }
.aba { display: none; }
.aba.ativa { display: block; }

@media (max-width: 768px) {
  .info-grid { grid-template-columns: 1fr; }
  .passo .rotulo { font-size: 0.68rem; }
  .progresso { padding: 18px; }
}
</style>
@endsection

@section('content')
@php
  $nivel        = $progresso['nivel'];
  $temCitacao   = $processo->documentos->where('tipo', 'citacao')->isNotEmpty();
  $temRecurso   = $processo->documentos->where('tipo', 'recurso')->isNotEmpty();
@endphp

<section class="page-hero" data-screen-label="Detalhe do Processo">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.processos.index') }}">Gestão de Processos</a><span class="sep">/</span>
      <span>{{ $processo->numero }}</span>
    </div>
    <h1>Processo {{ $processo->numero }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">

    {{-- Mensagens --}}
    @if (session('success'))
      <div class="flash flash-ok">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="flash flash-err">
        Corrija os campos abaixo:
        <ul>@foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
      </div>
    @endif

    {{-- Barra de progresso --}}
    <div class="progresso">
      <div class="progresso-topo">
        <span class="titulo">
          Andamento ·
          <span class="badge badge-{{ $processo->situacao }}">{{ $situacoes[$processo->situacao] }}</span>
        </span>
        <span class="pct">{{ $progresso['percentual'] }}%</span>
      </div>
      <div class="passos">
        @foreach ($progresso['etapas'] as $i => $etapa)
          @php $ordem = $i + 1; @endphp
          <button type="button" class="passo {{ $ordem < $nivel ? 'concluido' : ($ordem == $nivel ? 'atual' : '') }}"
                  data-passo="{{ $etapa['chave'] }}">
            <span class="bola">{{ $ordem < $nivel ? '✓' : $ordem }}</span>
            <span class="rotulo">{{ $etapa['rotulo'] }}</span>
          </button>
        @endforeach
      </div>
    </div>

    {{-- Navegação das abas --}}
    <div class="abas-nav">
      <button type="button" class="aba-btn" data-aba-btn="processo">
        Processo &amp; Origem
      </button>
      <button type="button" class="aba-btn" data-aba-btn="citacao">
        Citação @if ($temCitacao)<span class="check">✓</span>@endif
      </button>
      <button type="button" class="aba-btn" data-aba-btn="julgamento">
        Julgamento @if ($nivel >= 3)<span class="check">✓</span>@endif
      </button>
      <button type="button" class="aba-btn" data-aba-btn="recurso">
        Recurso @if ($processo->documentos->where('tipo', 'decisao_recurso')->isNotEmpty())<span class="check">✓</span>@endif
      </button>
    </div>

    {{-- ============================================================ --}}
    {{-- ABA 1 · PROCESSO & ORIGEM                                    --}}
    {{-- ============================================================ --}}
    <div class="aba" data-aba="processo">
      <div class="page-section">
        <div class="section-head">
          <h2>Informações do Processo</h2>
          <a href="{{ route('admin.processos.edit', $processo) }}" class="btn-secondary">Editar processo</a>
        </div>

        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Número</div>
            <div class="info-value">{{ $processo->numero }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Situação</div>
            <div class="info-value">
              <span class="badge badge-{{ $processo->situacao }}">{{ $situacoes[$processo->situacao] }}</span>
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Assunto</div>
            <div class="info-value">{{ $processo->assunto }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Competição</div>
            <div class="info-value">{{ $processo->competicao }}</div>
          </div>
          @if ($processo->denunciado)
          <div class="info-item">
            <div class="info-label">Denunciado</div>
            <div class="info-value">{{ $processo->denunciado }}</div>
          </div>
          @endif
          @if ($processo->clube)
          <div class="info-item">
            <div class="info-label">Clube</div>
            <div class="info-value">{{ $processo->clube }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- ORIGEM --}}
      <div class="page-section">
        <div class="section-head">
          <h2>Origem do Processo</h2>
        </div>

        <div class="form-card">
          <form method="POST" action="{{ route('documentos.store', $processo) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="origem">

            <div class="field">
              <label>Título da Origem <span class="req">*</span></label>
              <input type="text" name="titulo" placeholder="ex: Ofício de denúncia" required>
            </div>

            <div class="field">
              <label>Descrição</label>
              <textarea name="descricao" placeholder="ex: Denúncia contra agressão física em partida..."></textarea>
            </div>

            <div class="field">
              <label>Arquivo (PDF, imagem, etc.)</label>
              <input type="file" name="arquivo" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            </div>

            <div class="field">
              <label>Data do Documento</label>
              <input type="date" name="data">
            </div>

            <button type="submit" class="btn-primary">Adicionar Origem</button>
          </form>
        </div>

        <ul class="doc-list">
          @forelse ($processo->documentos->where('tipo', 'origem') as $doc)
          <li class="doc-item">
            <div class="doc-info">
              <div class="doc-title">{{ $doc->titulo }}</div>
              <div class="doc-meta">
                @if ($doc->data) {{ $doc->data->format('d/m/Y') }} · @endif
                {{ $doc->descricao }}
              </div>
            </div>
            <div class="doc-actions">
              @if ($doc->arquivo)
                <a href="{{ route('documentos.download', $doc) }}" class="btn-small btn-download">Download</a>
              @endif
              <form method="POST" action="{{ route('documentos.destroy', $doc) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover?')">Remover</button>
              </form>
            </div>
          </li>
          @empty
          <li class="empty-state">Nenhuma origem registrada</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ABA 2 · CITAÇÃO                                              --}}
    {{-- ============================================================ --}}
    <div class="aba" data-aba="citacao">
      <div class="page-section">
        <div class="section-head">
          <h2>Citação (Obrigatória para Julgamento)</h2>
        </div>

        <div class="form-card">
          <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 14px; background: #e3f2fd; padding: 10px; border-radius: var(--radius); border-left: 3px solid var(--gold);">
            Ao adicionar uma citação, o status será automaticamente atualizado para <strong>Aguardando Agendamento</strong>.
          </p>
          <form method="POST" action="{{ route('documentos.store', $processo) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="citacao">

            <div class="field">
              <label>Título da Citação <span class="req">*</span></label>
              <input type="text" name="titulo" placeholder="ex: Citação da Procurador" required>
            </div>

            <div class="field">
              <label>Arquivo (PDF) <span class="req">*</span></label>
              <input type="file" name="arquivo" accept=".pdf" required>
            </div>

            <div class="field">
              <label>Data da Citação</label>
              <input type="date" name="data">
            </div>

            <button type="submit" class="btn-primary">Adicionar Citação</button>
          </form>
        </div>

        <ul class="doc-list">
          @forelse ($processo->documentos->where('tipo', 'citacao') as $doc)
          <li class="doc-item">
            <div class="doc-info">
              <div class="doc-title">{{ $doc->titulo }}</div>
              <div class="doc-meta">
                @if ($doc->data) {{ $doc->data->format('d/m/Y') }} @endif
              </div>
            </div>
            <div class="doc-actions">
              @if ($doc->arquivo)
                <a href="{{ route('documentos.download', $doc) }}" class="btn-small btn-download">Download</a>
              @endif
              <form method="POST" action="{{ route('documentos.destroy', $doc) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover?')">Remover</button>
              </form>
            </div>
          </li>
          @empty
          <li class="empty-state">Nenhuma citação registrada — processo não pode ser agendado</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ABA 3 · JULGAMENTO                                           --}}
    {{-- ============================================================ --}}
    <div class="aba" data-aba="julgamento">
      <div class="page-section">
        <div class="section-head">
          <h2>Julgamento</h2>
        </div>

        <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 18px; background: #fff8e1; padding: 12px; border-radius: var(--radius); border-left: 3px solid var(--gold);">
          O agendamento e o lançamento do resultado são feitos no módulo de
          <a href="{{ route('admin.pautas.index') }}" style="color: var(--navy-900); font-weight: 700;">Pautas</a>.
          Ao registrar a Ata de Julgamento na pauta, o processo é movido automaticamente para o período de recurso.
        </p>

        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Relator</div>
            <div class="info-value">{{ $processo->relator ?: '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Data de Julgamento</div>
            <div class="info-value">{{ optional($processo->data_julgamento)->format('d/m/Y') ?: '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Resultado</div>
            <div class="info-value">{{ $processo->resultado ?: '—' }}</div>
          </div>
        </div>

        <div class="section-head" style="margin-top: 24px;">
          <h2 style="font-size: 1.1rem;">Ata de Decisão</h2>
        </div>

        @php
          $pautasComAta = $processo->pautas()
            ->whereHas('documentos', fn($q) => $q->where('tipo', 'ata'))
            ->with('documentos')
            ->get();
        @endphp

        @if ($pautasComAta->isNotEmpty())
          <ul class="doc-list">
            @foreach ($pautasComAta as $pauta)
              @foreach ($pauta->documentos->where('tipo', 'ata') as $ata)
              <li class="doc-item">
                <div class="doc-info">
                  <div class="doc-title">{{ $ata->titulo }}</div>
                  <div class="doc-meta">
                    Pauta: {{ $pauta->numero }} ·
                    @if ($ata->data) {{ $ata->data->format('d/m/Y') }} @endif
                  </div>
                </div>
                <div class="doc-actions">
                  @if ($ata->arquivo)
                    <a href="{{ route('documentos.download', $ata) }}" class="btn-small btn-download">Download</a>
                  @endif
                </div>
              </li>
              @endforeach
            @endforeach
          </ul>
        @else
          <div class="empty-state">Nenhuma ata de decisão — processo ainda não foi julgado</div>
        @endif
      </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ABA 4 · RECURSO                                              --}}
    {{-- ============================================================ --}}
    <div class="aba" data-aba="recurso">
      <div class="page-section">
        <div class="section-head">
          <h2>Recurso</h2>
        </div>

        <div class="form-card">
          <h3 style="margin: 0 0 14px; font-size: 0.95rem; color: var(--ink);">Adicionar Recurso</h3>
          <form method="POST" action="{{ route('documentos.store', $processo) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="recurso">

            <div class="field">
              <label>Título do Recurso <span class="req">*</span></label>
              <input type="text" name="titulo" placeholder="ex: Recurso apresentado" required>
            </div>

            <div class="field">
              <label>Arquivo (PDF) <span class="req">*</span></label>
              <input type="file" name="arquivo" accept=".pdf" required>
            </div>

            <div class="field">
              <label>Data do Recurso</label>
              <input type="date" name="data">
            </div>

            <button type="submit" class="btn-primary">Adicionar Recurso</button>
          </form>
        </div>

        <ul class="doc-list">
          @forelse ($processo->documentos->where('tipo', 'recurso') as $doc)
          <li class="doc-item">
            <div class="doc-info">
              <div class="doc-title">{{ $doc->titulo }}</div>
              <div class="doc-meta">
                @if ($doc->data) {{ $doc->data->format('d/m/Y') }} @endif
              </div>
            </div>
            <div class="doc-actions">
              @if ($doc->arquivo)
                <a href="{{ route('documentos.download', $doc) }}" class="btn-small btn-download">Download</a>
              @endif
              <form method="POST" action="{{ route('documentos.destroy', $doc) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover?')">Remover</button>
              </form>
            </div>
          </li>
          @empty
          <li class="empty-state">Nenhum recurso registrado</li>
          @endforelse
        </ul>

        {{-- Decisão do Recurso --}}
        @if ($temRecurso)
        <div class="form-card" style="margin-top: 20px;">
          <h3 style="margin: 0 0 14px; font-size: 0.95rem; color: var(--ink);">Decisão do Recurso</h3>
          <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 14px; background: #e3f2fd; padding: 10px; border-radius: var(--radius); border-left: 3px solid var(--gold);">
            O status será automaticamente atualizado: se <strong>aceito</strong> → "Recurso Aceito"; se <strong>negado</strong> → "Julgado".
          </p>
          <form method="POST" action="{{ route('documentos.store', $processo) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="decisao_recurso">

            <div class="field">
              <label>Título <span class="req">*</span></label>
              <input type="text" name="titulo" placeholder="ex: Decisão do Recurso" required>
            </div>

            <div class="field">
              <label>Arquivo (PDF) <span class="req">*</span></label>
              <input type="file" name="arquivo" accept=".pdf" required>
            </div>

            <div class="field">
              <label>Status do Recurso <span class="req">*</span></label>
              <select name="status_recurso" required>
                <option value="">-- Selecione --</option>
                <option value="aceito">Aceito</option>
                <option value="negado">Negado</option>
              </select>
            </div>

            <button type="submit" class="btn-primary">Adicionar Decisão de Recurso</button>
          </form>
        </div>
        @endif

        <ul class="doc-list">
          @forelse ($processo->documentos->where('tipo', 'decisao_recurso') as $doc)
          <li class="doc-item">
            <div class="doc-info">
              <div class="doc-title">{{ $doc->titulo }}</div>
              <div class="doc-meta">
                @if ($doc->status_recurso)
                  <span style="display: inline-block; background: {{ $doc->status_recurso === 'aceito' ? '#e8f5e9' : '#ffebee' }}; color: {{ $doc->status_recurso === 'aceito' ? '#2e7d32' : '#c62828' }}; padding: 2px 8px; border-radius: var(--radius); font-weight: 600;">
                    {{ ucfirst($doc->status_recurso) }}
                  </span>
                @endif
                @if ($doc->data) · {{ $doc->data->format('d/m/Y') }} @endif
              </div>
            </div>
            <div class="doc-actions">
              @if ($doc->arquivo)
                <a href="{{ route('documentos.download', $doc) }}" class="btn-small btn-download">Download</a>
              @endif
              <form method="POST" action="{{ route('documentos.destroy', $doc) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover?')">Remover</button>
              </form>
            </div>
          </li>
          @empty
          <li class="empty-state">Nenhuma decisão de recurso registrada</li>
          @endforelse
        </ul>
      </div>
    </div>

  </div>
</section>
@endsection

@section('scripts')
<script>
  (function () {
    var validas = ['processo', 'citacao', 'julgamento', 'recurso'];

    function ativarAba(chave) {
      if (validas.indexOf(chave) === -1) chave = 'processo';
      document.querySelectorAll('[data-aba]').forEach(function (p) {
        p.classList.toggle('ativa', p.dataset.aba === chave);
      });
      document.querySelectorAll('[data-aba-btn]').forEach(function (b) {
        b.classList.toggle('ativa', b.dataset.abaBtn === chave);
      });
      document.querySelectorAll('[data-passo]').forEach(function (s) {
        s.classList.toggle('aberta', s.dataset.passo === chave);
      });
      if (history.replaceState) history.replaceState(null, '', '#' + chave);
    }

    document.querySelectorAll('[data-aba-btn]').forEach(function (b) {
      b.addEventListener('click', function () { ativarAba(b.dataset.abaBtn); });
    });
    document.querySelectorAll('[data-passo]').forEach(function (s) {
      s.addEventListener('click', function () { ativarAba(s.dataset.passo); });
    });

    ativarAba((location.hash || '').replace('#', ''));
  })();
</script>
@endsection
