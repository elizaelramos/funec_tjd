@extends('layouts.app')

@php $editando = $pauta->exists; @endphp

@section('title', ($editando ? 'Editar' : 'Nova') . ' Pauta — TJD · FUNEC')

@section('head')
<style>
.form-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:28px;max-width:880px;}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.field{display:flex;flex-direction:column;gap:6px;margin-bottom:18px;}
.field label{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);}
.field label .req{color:var(--danger);}
.field input, .field select{font-family:inherit;font-size:.95rem;padding:11px 14px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface-2);color:var(--ink);width:100%;}
.field input:focus, .field select:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(195,154,63,.15);}
.field .err{color:var(--danger);font-size:.8rem;font-weight:600;}
.field.has-error input, .field.has-error select{border-color:var(--danger);}
.form-actions{display:flex;gap:12px;margin-top:8px;}
.errors-box{background:#fdecea;border:1px solid #f5c2bb;color:#b3261e;border-radius:var(--radius);padding:14px 18px;margin-bottom:22px;font-size:.9rem;}
.errors-box ul{margin:6px 0 0;padding-left:20px;}
.processos-list{background:var(--surface-2);border:1px solid var(--line);border-radius:var(--radius);padding:14px;max-height:300px;overflow-y:auto;}
.processos-list label{display:flex;align-items:center;gap:8px;margin-bottom:10px;cursor:pointer;font-size:.9rem;font-weight:normal;text-transform:none;letter-spacing:normal;}
.processos-list input[type="checkbox"]{width:18px;height:18px;cursor:pointer;flex-shrink:0;}
@media (max-width:680px){ .grid2{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Formulário de Pauta">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.pautas.index') }}">Gestão de Pautas</a><span class="sep">/</span>
      <span>{{ $editando ? 'Editar ' . $pauta->numero : 'Nova pauta' }}</span>
    </div>
    <h1>{{ $editando ? 'Editar pauta' : 'Agendar nova sessão de julgamento' }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if ($errors->any())
      <div class="errors-box">
        Corrija os campos abaixo:
        <ul>
          @foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form class="form-card" method="POST"
          action="{{ $editando ? route('admin.pautas.update', $pauta) : route('admin.pautas.store') }}">
      @csrf
      @if ($editando) @method('PUT') @endif

      <div class="grid2">
        <div class="field {{ $errors->has('numero') ? 'has-error' : '' }}">
          <label>Número da Pauta <span class="req">*</span></label>
          <input type="text" name="numero" value="{{ old('numero', $pauta->numero) }}" placeholder="5ª Sessão">
          @error('numero')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="field {{ $errors->has('titulo') ? 'has-error' : '' }}">
        <label>Título <span class="req">*</span></label>
        <input type="text" name="titulo" value="{{ old('titulo', $pauta->titulo) }}" placeholder="5ª Sessão de Julgamento — Comissão Disciplinar">
        @error('titulo')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('orgao_julgador') ? 'has-error' : '' }}">
          <label>Órgão Julgador <span class="req">*</span></label>
          <select name="orgao_julgador">
            <option value="">Selecione...</option>
            @foreach ($orgaos as $valor => $rotulo)
              <option value="{{ $valor }}" @selected(old('orgao_julgador', $pauta->orgao_julgador) === $valor)>{{ $rotulo }}</option>
            @endforeach
          </select>
          @error('orgao_julgador')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('situacao') ? 'has-error' : '' }}">
          <label>Situação <span class="req">*</span></label>
          <select name="situacao">
            @foreach ($situacoes as $valor => $rotulo)
              <option value="{{ $valor }}" @selected(old('situacao', $pauta->situacao ?: 'agendada') === $valor)>{{ $rotulo }}</option>
            @endforeach
          </select>
          @error('situacao')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('data') ? 'has-error' : '' }}">
          <label>Data <span class="req">*</span></label>
          <input type="date" name="data" value="{{ old('data', optional($pauta->data)->format('Y-m-d')) }}">
          @error('data')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('hora') ? 'has-error' : '' }}">
          <label>Hora <span class="req">*</span></label>
          <input type="time" name="hora" value="{{ old('hora', $pauta->hora) }}">
          @error('hora')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="field {{ $errors->has('local') ? 'has-error' : '' }}">
        <label>Local</label>
        <input type="text" name="local" value="{{ old('local', $pauta->local) }}" placeholder="Sede da FUNEC · Sala de Sessões">
        @error('local')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="field {{ $errors->has('processos') ? 'has-error' : '' }}">
        <label>Processos a Julgar</label>
        <div class="processos-list">
          @forelse ($processos as $processo)
            <label>
              <input type="checkbox" name="processos[]" value="{{ $processo->id }}"
                @checked(in_array($processo->id, (array)(old('processos') ?? $pauta->processos->pluck('id')->toArray())))>
              <strong>{{ $processo->numero }}</strong> — {{ $processo->assunto }} ({{ $processo->competicao }})
            </label>
          @empty
            <p style="color:var(--muted);margin:0;">Nenhum processo disponível. <a href="{{ route('admin.processos.create') }}">Criar novo processo</a></p>
          @endforelse
        </div>
        @error('processos')<span class="err">{{ $message }}</span>@enderror
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Agendar sessão' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.pautas.index') }}">Cancelar</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:auto;">
          @csrf
          <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
      </div>
    </form>
  </div>
</section>
@endsection
