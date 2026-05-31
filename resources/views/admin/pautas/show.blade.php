@extends('layouts.app')

@section('title', 'Pauta ' . $pauta->numero . ' — TJD · FUNEC')

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
.badge-agendada { background: #e3f2fd; color: #0d47a1; }
.badge-julgada { background: #e8f5e9; color: #1b5e20; }

.form-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 20px; margin-bottom: 18px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.field label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
.field label .req { color: var(--danger); }
.field input, .field select { font-family: inherit; font-size: 0.95rem; padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface-2); color: var(--ink); width: 100%; }
.field input:focus, .field select:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(195,154,63,.15); }

.processos-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.processos-table th { background: var(--navy-900); color: white; padding: 12px; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
.processos-table td { padding: 12px; border-bottom: 1px solid var(--line); }
.processos-table tr:hover { background: var(--surface); }
.processos-table .badge-warning { background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: var(--radius); font-size: 0.75rem; font-weight: 600; }

.doc-list { list-style: none; padding: 0; margin: 0; }
.doc-item { background: var(--surface-2); padding: 14px; border-radius: var(--radius); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
.doc-info { flex: 1; }
.doc-title { font-weight: 600; color: var(--ink); margin-bottom: 4px; }
.doc-meta { font-size: 0.8rem; color: var(--muted); }
.doc-actions { display: flex; gap: 8px; }
.btn-small { padding: 6px 12px; font-size: 0.8rem; border: none; border-radius: var(--radius); cursor: pointer; }
.btn-download { background: var(--gold); color: var(--navy-900); }
.btn-delete { background: var(--danger); color: white; }
.btn-download:hover, .btn-delete:hover { opacity: 0.9; }
.btn-primary { background: var(--navy-900); color: white; padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-secondary { background: var(--line); color: var(--ink); padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-primary:hover, .btn-secondary:hover { opacity: 0.9; }
.empty-state { background: var(--surface); padding: 20px; border-radius: var(--radius); color: var(--muted); text-align: center; }
@media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } .processos-table { font-size: 0.85rem; } }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Detalhe da Pauta">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.pautas.index') }}">Gestão de Pautas</a><span class="sep">/</span>
      <span>{{ $pauta->numero }}</span>
    </div>
    <h1>{{ $pauta->numero }} — {{ $pauta->titulo }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    {{-- Resumo da pauta --}}
    <div class="page-section">
      <div class="section-head">
        <h2>Informações da Pauta</h2>
        <div style="display: flex; gap: 10px;">
          @if ($pauta->situacao === 'agendada' && $pauta->processos->isNotEmpty())
            <a href="{{ route('admin.pautas.resultados', $pauta) }}" class="btn-primary" style="background: #4caf50;">
              Inserir Resultados
            </a>
          @endif
          <a href="{{ route('admin.pautas.edit', $pauta) }}" class="btn-secondary">Editar pauta</a>
        </div>
      </div>

      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Número</div>
          <div class="info-value">{{ $pauta->numero }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Situação</div>
          <div class="info-value">
            <span class="badge badge-{{ $pauta->situacao }}">{{ $situacoes[$pauta->situacao] }}</span>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Órgão Julgador</div>
          <div class="info-value">{{ $pauta->orgao_julgador }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Data</div>
          <div class="info-value">{{ $pauta->data->format('d/m/Y') }}</div>
        </div>
      </div>
    </div>

    {{-- Processos agendados --}}
    <div class="page-section">
      <div class="section-head">
        <h2>Processos Agendados</h2>
      </div>

      @if ($pauta->processos->isEmpty())
        <div class="empty-state">Nenhum processo agendado para esta pauta</div>
      @else
        <div style="overflow-x: auto;">
          <table class="processos-table">
            <thead>
              <tr>
                <th>Número</th>
                <th>Assunto</th>
                <th>Denunciado</th>
                <th>Citação</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pauta->processos as $p)
              <tr>
                <td><strong>{{ $p->numero }}</strong></td>
                <td>{{ $p->assunto }}</td>
                <td>{{ $p->denunciado ?? '—' }}</td>
                <td>
                  @if ($p->temCitacao())
                    <span style="color: var(--success);">✓ Sim</span>
                  @else
                    <span class="badge-warning">✗ Sem citação</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- ATA DE JULGAMENTO --}}
    @if ($pauta->documentos->where('tipo', 'ata')->isNotEmpty())
    <div class="page-section">
      <div class="section-head">
        <h2>Ata de Julgamento</h2>
      </div>

      <ul class="doc-list">
        @foreach ($pauta->documentos->where('tipo', 'ata') as $ata)
        <li class="doc-item">
          <div class="doc-info">
            <div class="doc-title">{{ $ata->titulo }}</div>
            <div class="doc-meta">
              Adicionado em {{ $ata->created_at->format('d/m/Y H:i') }}
            </div>
          </div>
          <div class="doc-actions">
            @if ($ata->arquivo)
              <a href="{{ route('documentos.download', $ata) }}" class="btn-small btn-download">Download</a>
            @endif
            <form method="POST" action="{{ route('documentos.destroy', $ata) }}" style="display:inline;">
              @csrf @method('DELETE')
              <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover?')">Remover</button>
            </form>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
    @endif

  </div>
</section>
@endsection
