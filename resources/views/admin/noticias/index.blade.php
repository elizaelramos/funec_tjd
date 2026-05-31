@extends('layouts.app')

@php
  $status_options = \App\Http\Controllers\NoticiaController::STATUS;
@endphp

@section('title', 'Notícias — TJD · FUNEC')

@section('head')
<style>
.page-section { margin-bottom: 32px; }
.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 2px solid var(--gold); padding-bottom: 12px; }
.section-head h2 { font-size: 1.3rem; margin: 0; color: var(--navy-900); }
.flash{background:#e8f3ec;border:1px solid #c5e2d0;color:#2f7d51;border-radius:var(--radius);padding:12px 18px;margin-bottom:22px;font-size:.92rem;font-weight:600;}
.btn-primary { background: var(--navy-900); color: white; padding: 11px 18px; border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; }
.btn-primary:hover { opacity: 0.9; }
.btn-small { padding: 6px 12px; font-size: 0.8rem; border: none; border-radius: var(--radius); cursor: pointer; }
.btn-edit { background: var(--gold); color: var(--navy-900); }
.btn-delete { background: var(--danger); color: white; }
.btn-edit:hover, .btn-delete:hover { opacity: 0.9; }
.badge { display: inline-block; padding: 6px 12px; border-radius: var(--radius); font-size: 0.8rem; font-weight: 600; }
.badge-rascunho { background: #fff3e0; color: #e65100; }
.badge-publicada { background: #e8f5e9; color: #1b5e20; }
.table-wrap { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; }
.table-wrap th { background: var(--surface-2); padding: 14px 18px; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color: var(--muted); letter-spacing: 0.05em; border-bottom: 1px solid var(--line); }
.table-wrap td { padding: 14px 18px; border-bottom: 1px solid var(--line); }
.table-wrap tr:last-child td { border-bottom: none; }
.table-wrap td.actions { display: flex; gap: 8px; }
.empty-state { background: var(--surface); padding: 20px; border-radius: var(--radius); color: var(--muted); text-align: center; }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Gerenciar Notícias">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.processos.index') }}">Gestão</a><span class="sep">/</span>
      <span>Notícias</span>
    </div>
    <h1>Notícias</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if (session('ok'))
      <div class="flash">{{ session('ok') }}</div>
    @endif

    <div class="page-section">
      <div class="section-head">
        <h2>Todas as Notícias</h2>
        <a href="{{ route('admin.noticias.create') }}" class="btn-primary">+ Nova Notícia</a>
      </div>

      @if ($noticias->isEmpty())
        <div class="empty-state">Nenhuma notícia cadastrada.</div>
      @else
        <table class="table-wrap">
          <thead>
            <tr>
              <th style="flex: 1;">Título</th>
              <th style="width: 120px;">Categoria</th>
              <th style="width: 100px;">Status</th>
              <th style="width: 100px;">Data</th>
              <th style="width: 140px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($noticias as $noticia)
            <tr>
              <td>{{ $noticia->titulo }}</td>
              <td>{{ $noticia->categoria }}</td>
              <td><span class="badge badge-{{ $noticia->status }}">{{ $status_options[$noticia->status] ?? $noticia->status }}</span></td>
              <td>{{ $noticia->data->format('d/m/Y') }}</td>
              <td class="actions">
                <a href="{{ route('admin.noticias.edit', $noticia) }}" class="btn-small btn-edit">Editar</a>
                <form method="POST" action="{{ route('admin.noticias.destroy', $noticia) }}" style="display:inline;">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-small btn-delete" onclick="return confirm('Remover esta notícia?')">Excluir</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</section>
@endsection
