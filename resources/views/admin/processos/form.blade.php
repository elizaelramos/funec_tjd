@extends('layouts.admin')

@php $editando = $processo->exists; @endphp

@section('title', ($editando ? 'Editar' : 'Novo') . ' Processo — TJD · FUNEC')

@section('head')
<style>
.form-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:28px;max-width:880px;}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.field{display:flex;flex-direction:column;gap:6px;margin-bottom:18px;}
.field label{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);}
.field label .req{color:var(--danger);}
.field input, .field select, .field textarea{font-family:inherit;font-size:.95rem;padding:11px 14px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface-2);color:var(--ink);width:100%;}
.field input:focus, .field select:focus, .field textarea:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(195,154,63,.15);}
.field textarea{resize:vertical;min-height:100px;}
.field .err{color:var(--danger);font-size:.8rem;font-weight:600;}
.field.has-error input, .field.has-error select{border-color:var(--danger);}
.form-actions{display:flex;gap:12px;margin-top:8px;}
.errors-box{background:#fdecea;border:1px solid #f5c2bb;color:#b3261e;border-radius:var(--radius);padding:14px 18px;margin-bottom:22px;font-size:.9rem;}
.errors-box ul{margin:6px 0 0;padding-left:20px;}
@media (max-width:680px){ .grid2{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Formulário de Processo">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.processos.index') }}">Gestão de Processos</a><span class="sep">/</span>
      <span>{{ $editando ? 'Editar ' . $processo->numero : 'Novo processo' }}</span>
    </div>
    <h1>{{ $editando ? 'Editar processo' : 'Novo processo' }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    {{-- Resumo de erros de validação --}}
    @if ($errors->any())
      <div class="errors-box">
        Corrija os campos abaixo:
        <ul>
          @foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form class="form-card" method="POST"
          action="{{ $editando ? route('admin.processos.update', $processo) : route('admin.processos.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if ($editando) @method('PUT') @endif

      <div class="grid2">
        <div class="field {{ $errors->has('numero') ? 'has-error' : '' }}">
          <label>Número <span class="req">*</span></label>
          <input type="text" name="numero" value="{{ old('numero', $processo->numero) }}" placeholder="031/2026">
          @error('numero')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="field {{ $errors->has('assunto') ? 'has-error' : '' }}">
        <label>Assunto <span class="req">*</span></label>
        <input type="text" name="assunto" value="{{ old('assunto', $processo->assunto) }}" placeholder="Denúncia por agressão física a adversário">
        @error('assunto')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('competicao') ? 'has-error' : '' }}">
          <label>Competição <span class="req">*</span></label>
          <input type="text" name="competicao" value="{{ old('competicao', $processo->competicao) }}" placeholder="Campeonato Corumbaense — Série A">
          @error('competicao')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      @if ($editando)
      <div class="grid2">
        <div class="field {{ $errors->has('situacao') ? 'has-error' : '' }}">
          <label>Situação <span class="req">*</span></label>
          <select name="situacao">
            @foreach ($situacoes as $valor => $rotulo)
              <option value="{{ $valor }}" @selected(old('situacao', $processo->situacao) === $valor)>{{ $rotulo }}</option>
            @endforeach
          </select>
          @error('situacao')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('relator') ? 'has-error' : '' }}">
          <label>Relator</label>
          <input type="text" name="relator" value="{{ old('relator', $processo->relator) }}">
          @error('relator')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>
      @else
      <div class="grid2">
        <div class="field {{ $errors->has('relator') ? 'has-error' : '' }}">
          <label>Relator</label>
          <input type="text" name="relator" value="{{ old('relator', $processo->relator) }}">
          @error('relator')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>
      @endif

      <div class="grid2">
        <div class="field {{ $errors->has('enquadramento') ? 'has-error' : '' }}">
          <label>Enquadramento</label>
          <input type="text" name="enquadramento" value="{{ old('enquadramento', $processo->enquadramento) }}" placeholder="Art. 254-A, CBJD">
          @error('enquadramento')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('clube') ? 'has-error' : '' }}">
          <label>Clube</label>
          <input type="text" name="clube" value="{{ old('clube', $processo->clube) }}">
          @error('clube')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('denunciante') ? 'has-error' : '' }}">
          <label>Denunciante</label>
          <input type="text" name="denunciante" value="{{ old('denunciante', $processo->denunciante) }}" placeholder="Procuradoria do TJD">
          @error('denunciante')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('denunciado') ? 'has-error' : '' }}">
          <label>Denunciado</label>
          <input type="text" name="denunciado" value="{{ old('denunciado', $processo->denunciado) }}" placeholder="Atleta — Operário FC">
          @error('denunciado')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="field {{ $errors->has('partida') ? 'has-error' : '' }}">
        <label>Partida</label>
        <input type="text" name="partida" value="{{ old('partida', $processo->partida) }}" placeholder="Operário FC × Corumbaense EC">
        @error('partida')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('resultado') ? 'has-error' : '' }}">
          <label>Resultado</label>
          <input type="text" name="resultado" value="{{ old('resultado', $processo->resultado) }}" placeholder="Suspensão · 4 partidas">
          @error('resultado')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('data_julgamento') ? 'has-error' : '' }}">
          <label>Data de julgamento</label>
          <input type="date" name="data_julgamento" value="{{ old('data_julgamento', optional($processo->data_julgamento)->format('Y-m-d')) }}">
          @error('data_julgamento')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--line);">
      <h3 style="font-size: 1rem; margin-bottom: 18px;">Documentos</h3>

      {{-- Origem (apenas criação) --}}
      @if (!$editando)
      <h4 style="font-size: 0.9rem; color: var(--muted); margin-bottom: 14px;">Origem do Processo (Opcional)</h4>

      <div class="field {{ $errors->has('origem_titulo') ? 'has-error' : '' }}">
        <label>Título da origem</label>
        <input type="text" name="origem_titulo" value="{{ old('origem_titulo') }}" placeholder="ex: Ofício de denúncia, Súmula da partida">
        @error('origem_titulo')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="field {{ $errors->has('origem_descricao') ? 'has-error' : '' }}">
        <label>Descrição</label>
        <textarea name="origem_descricao" placeholder="Detalhes sobre a origem do processo...">{{ old('origem_descricao') }}</textarea>
        @error('origem_descricao')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="field {{ $errors->has('origem_arquivo') ? 'has-error' : '' }}">
        <label>Arquivo (PDF, imagem, etc.)</label>
        <input type="file" name="origem_arquivo" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.zip">
        @error('origem_arquivo')<span class="err">{{ $message }}</span>@enderror
      </div>
      @else
      {{-- Mostrar origem na edição --}}
      @php
        $origem = $processo->documentos()->where('tipo', 'origem')->first();
      @endphp
      @if ($origem)
      <div style="background: var(--surface); padding: 14px; border-radius: var(--radius); margin-bottom: 18px; border-left: 3px solid var(--gold);">
        <h4 style="margin: 0 0 8px; font-size: 0.9rem;">Origem do Processo</h4>
        <p style="margin: 0 0 6px; font-size: 0.85rem;"><strong>{{ $origem->titulo }}</strong></p>
        @if ($origem->arquivo)
          <p style="margin: 0; font-size: 0.8rem; color: var(--muted);">📎 {{ $origem->nome_original }}</p>
        @endif
      </div>
      @endif
      @endif

      {{-- Citação (em criação e edição) --}}
      <h4 style="font-size: 0.9rem; color: var(--muted); margin-bottom: 14px; margin-top: 18px;">Citação (obrigatória para julgamento)</h4>

      @php
        $citacao = $processo->documentos()->where('tipo', 'citacao')->first();
      @endphp

      @if ($citacao && $editando)
      <div style="background: #e8f5e9; padding: 14px; border-radius: var(--radius); margin-bottom: 18px; border-left: 3px solid #4caf50;">
        <p style="margin: 0; font-size: 0.85rem;"><strong>✓ Citação já registrada</strong></p>
        <p style="margin: 4px 0 0; font-size: 0.8rem; color: var(--muted);">{{ $citacao->titulo }} • 📎 {{ $citacao->nome_original }}</p>
      </div>
      @endif

      <div class="field {{ $errors->has('citacao_arquivo') ? 'has-error' : '' }}">
        <label>Arquivo de Citação (PDF) @if (!$citacao)<span class="req">*</span>@endif</label>
        <input type="file" name="citacao_arquivo" accept=".pdf" @if (!$citacao && !$editando) required @endif>
        @error('citacao_arquivo')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar processo' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.processos.index') }}">Cancelar</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:auto;">
          @csrf
          <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
      </div>
    </form>
  </div>
</section>
@endsection
