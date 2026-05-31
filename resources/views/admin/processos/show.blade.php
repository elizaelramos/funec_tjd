@extends('layouts.app')

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
@media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
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
    {{-- Resumo do processo --}}
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

    {{-- CITAÇÃO --}}
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

    {{-- ATA DE DECISÃO (vinculada à Pauta) --}}
    <div class="page-section">
      <div class="section-head">
        <h2>Ata de Decisão</h2>
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

    {{-- RECURSO --}}
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
      @if ($processo->documentos->where('tipo', 'recurso')->isNotEmpty())
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
</section>
@endsection
