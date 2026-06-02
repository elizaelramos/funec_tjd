@extends('layouts.admin')

@section('title', 'Inserir Resultados — ' . $pauta->numero . ' — TJD · FUNEC')

@section('head')
<style>
.page-section { margin-bottom: 32px; }
.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 2px solid var(--gold); padding-bottom: 12px; }
.section-head h2 { font-size: 1.3rem; margin: 0; color: var(--navy-900); }
.info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-bottom: 20px; }
.info-item { background: var(--surface); padding: 14px; border-radius: var(--radius); border-left: 3px solid var(--gold); }
.info-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: 0.08em; margin-bottom: 4px; }
.info-value { font-size: 0.95rem; color: var(--ink); font-weight: 600; }

.form-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 20px; margin-bottom: 18px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.field label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
.field label .req { color: var(--danger); }
.field input, .field select, .field textarea { font-family: inherit; font-size: 0.95rem; padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface-2); color: var(--ink); width: 100%; }
.field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(195,154,63,.15); }
.field textarea { resize: vertical; min-height: 100px; }

.resultados-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.resultados-table th { background: var(--navy-900); color: white; padding: 12px; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
.resultados-table td { padding: 12px; border-bottom: 1px solid var(--line); }
.resultados-table tr:hover { background: var(--surface); }
.resultados-table .procno { font-family: var(--mono); font-size: 0.9rem; color: var(--navy-900); font-weight: 600; }
.resultado-field { width: 100%; }

.ata-section { background: #f3e5f5; padding: 18px; border-radius: var(--radius-lg); border-left: 4px solid #9c27b0; margin-bottom: 20px; }
.ata-section h3 { margin: 0 0 14px; font-size: 0.95rem; color: var(--navy-900); }

.form-actions { display: flex; gap: 12px; margin-top: 18px; }
.btn-primary { background: var(--navy-900); color: white; padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-primary:hover { opacity: 0.9; }
.btn-secondary { background: var(--line); color: var(--ink); padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-secondary:hover { opacity: 0.9; }

.alert { padding: 14px; border-radius: var(--radius); margin-bottom: 18px; font-size: 0.9rem; }
.alert-error { background: #ffebee; color: #c62828; border-left: 3px solid #c62828; }

@media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Inserir Resultados">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.pautas.index') }}">Gestão de Pautas</a><span class="sep">/</span>
      <a href="{{ route('admin.pautas.show', $pauta) }}">{{ $pauta->numero }}</a><span class="sep">/</span>
      <span>Inserir Resultados</span>
    </div>
    <h1>Inserir Resultados — {{ $pauta->numero }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    {{-- Resumo da pauta --}}
    <div class="page-section">
      <div class="section-head">
        <h2>Informações da Pauta</h2>
      </div>

      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Data</div>
          <div class="info-value">{{ $pauta->data->format('d/m/Y') }} às {{ $pauta->hora }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Órgão Julgador</div>
          <div class="info-value">{{ $pauta->orgao_julgador }}</div>
        </div>
      </div>
    </div>

    {{-- Formulário de resultados --}}
    <form method="POST" action="{{ route('admin.pautas.salvarResultados', $pauta) }}" enctype="multipart/form-data">
      @csrf

      {{-- Ata de Decisão --}}
      <div class="ata-section">
        <h3>Ata de Julgamento <span style="color: var(--danger);">*</span></h3>

        <div class="field">
          <label>Arquivo (PDF) <span class="req">*</span></label>
          <input type="file" name="ata_arquivo" accept=".pdf" required @if($errors->has('ata_arquivo')) autofocus @endif>
          @error('ata_arquivo')<span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>@enderror
        </div>

        <div class="field">
          <label>Título da Ata</label>
          <input type="text" name="ata_titulo" placeholder="ex: Ata de Julgamento — {{ $pauta->numero }}" value="{{ old('ata_titulo', 'Ata de Julgamento — ' . $pauta->numero) }}">
        </div>
      </div>

      {{-- Resultados dos processos --}}
      <div class="page-section">
        <div class="section-head">
          <h2>Resultados dos Processos</h2>
        </div>

        @if($errors->has('resultados'))
          <div class="alert alert-error">
            {{ $errors->first('resultados') }}
          </div>
        @endif

        <div style="overflow-x: auto;">
          <table class="resultados-table">
            <thead>
              <tr>
                <th>Processo</th>
                <th>Acusado</th>
                <th>Resultado <span style="color: var(--danger);">*</span></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pauta->processos as $i => $processo)
              <tr>
                <td><span class="procno">{{ $processo->numero }}</span></td>
                <td>{{ $processo->denunciado }}</td>
                <td>
                  <input
                    type="text"
                    name="resultados[{{ $processo->id }}]"
                    class="resultado-field"
                    placeholder="ex: Suspensão · 4 partidas"
                    value="{{ old('resultados.' . $processo->id, $processo->resultado) }}"
                    required
                  >
                  @error('resultados.' . $processo->id)
                    <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                  @enderror
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- Botões --}}
      <div class="form-actions">
        <button type="submit" class="btn-primary">Salvar Resultados e Ata</button>
        <a href="{{ route('admin.pautas.show', $pauta) }}" class="btn-secondary">Cancelar</a>
      </div>
    </form>

  </div>
</section>
@endsection
