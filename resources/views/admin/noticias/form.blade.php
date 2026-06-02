@extends('layouts.admin')

@php
  $editando = $noticia->exists;
@endphp

@section('title', ($editando ? 'Editar' : 'Nova') . ' Notícia — TJD · FUNEC')

@section('head')
<style>
.page-section { margin-bottom: 32px; }
.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 2px solid var(--gold); padding-bottom: 12px; }
.section-head h2 { font-size: 1.3rem; margin: 0; color: var(--navy-900); }
.form-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 20px; margin-bottom: 18px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.field label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
.field label .req { color: var(--danger); }
.field input, .field select, .field textarea { font-family: inherit; font-size: 0.95rem; padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface-2); color: var(--ink); width: 100%; }
.field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(195,154,63,.15); }
.field textarea { resize: vertical; min-height: 120px; }
.grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.btn-primary { background: var(--navy-900); color: white; padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-secondary { background: var(--line); color: var(--ink); padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-primary:hover, .btn-secondary:hover { opacity: 0.9; }
.form-actions { display: flex; gap: 12px; margin-top: 18px; }
.errors-box { background: #ffebee; border: 1px solid #ef5350; color: #c62828; border-radius: var(--radius); padding: 14px 18px; margin-bottom: 20px; }
.errors-box ul { margin: 0; padding-left: 20px; }
.errors-box li { margin-bottom: 6px; }
.err { display: block; font-size: 0.8rem; color: var(--danger); margin-top: 4px; }
.image-preview { margin-top: 12px; }
.image-preview img { max-width: 200px; border-radius: var(--radius); border: 1px solid var(--line); }
.image-preview-actions { margin-top: 10px; display: flex; gap: 10px; }
.image-preview-actions input { margin-right: 8px; }
.info-box { background: #e3f2fd; border-left: 3px solid var(--gold); padding: 10px; border-radius: var(--radius); font-size: 0.85rem; color: var(--muted); margin-bottom: 14px; }
@media (max-width: 768px) { .grid2 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Notícia">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.noticias.index') }}">Notícias</a><span class="sep">/</span>
      <span>{{ $editando ? 'Editar' : 'Nova' }}</span>
    </div>
    <h1>{{ $editando ? 'Editar Notícia' : 'Nova Notícia' }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="page-section">
      @if ($errors->any())
        <div class="errors-box">
          Corrija os campos abaixo:
          <ul>
            @foreach ($errors->all() as $erro)
              <li>{{ $erro }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="form-card">
        <form method="POST"
              action="{{ $editando ? route('admin.noticias.update', $noticia) : route('admin.noticias.store') }}"
              enctype="multipart/form-data">
          @csrf
          @if ($editando) @method('PUT') @endif

          <div class="grid2">
            <div class="field">
              <label>Título <span class="req">*</span></label>
              <input type="text" name="titulo" value="{{ old('titulo', $noticia->titulo) }}" placeholder="ex: Publicada pauta de julgamento" required>
              @error('titulo')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="field">
              <label>Categoria <span class="req">*</span></label>
              <select name="categoria" required>
                <option value="">-- Selecione --</option>
                @foreach ($categorias as $cat)
                  <option value="{{ $cat }}" @selected(old('categoria', $noticia->categoria) === $cat)>{{ $cat }}</option>
                @endforeach
              </select>
              @error('categoria')<span class="err">{{ $message }}</span>@enderror
            </div>
          </div>

          <div class="grid2">
            <div class="field">
              <label>Data <span class="req">*</span></label>
              <input type="date" name="data" value="{{ old('data', optional($noticia->data)->format('Y-m-d')) }}" required>
              @error('data')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="field">
              <label>Status <span class="req">*</span></label>
              <select name="status" required>
                @foreach ($status_options as $valor => $rotulo)
                  <option value="{{ $valor }}" @selected(old('status', $noticia->status) === $valor)>{{ $rotulo }}</option>
                @endforeach
              </select>
              @error('status')<span class="err">{{ $message }}</span>@enderror
            </div>
          </div>

          <div class="field">
            <label>Resumo (aparece nos cards da listagem)</label>
            <textarea name="resumo" placeholder="ex: Descrição breve da notícia...">{{ old('resumo', $noticia->resumo) }}</textarea>
            @error('resumo')<span class="err">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Conteúdo</label>
            <textarea name="conteudo" placeholder="ex: Texto completo da notícia..." style="min-height: 200px;">{{ old('conteudo', $noticia->conteudo) }}</textarea>
            @error('conteudo')<span class="err">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Link Externo (opcional — se preenchido, card aponta para esta URL)</label>
            <input type="text" name="link_externo" value="{{ old('link_externo', $noticia->link_externo) }}" placeholder="ex: https://exemplo.com/noticia">
            @error('link_externo')<span class="err">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Imagem (JPEG, PNG, WebP — máx. 5 MB)</label>
            <input type="file" name="imagem" accept=".jpg,.jpeg,.png,.webp">
            @error('imagem')<span class="err">{{ $message }}</span>@enderror

            @if ($editando && $noticia->imagem)
              <div class="image-preview">
                <img src="{{ asset('storage/' . $noticia->imagem) }}" alt="{{ $noticia->titulo }}">
                <div class="image-preview-actions">
                  <label>
                    <input type="checkbox" name="remover_imagem"> Remover imagem
                  </label>
                </div>
              </div>
            @endif
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-primary">{{ $editando ? 'Salvar Alterações' : 'Cadastrar Notícia' }}</button>
            <a href="{{ route('admin.noticias.index') }}" class="btn-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
