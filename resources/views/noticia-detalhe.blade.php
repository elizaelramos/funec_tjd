@extends('layouts.app')

@section('title', $noticia->titulo . ' — TJD · FUNEC')

@section('head')
<style>
.noticia-hero { background: var(--navy-900); color: white; padding: 60px 0; }
.noticia-hero .wrap { max-width: 900px; }
.noticia-hero h1 { margin: 0 0 20px; font-size: 2.2rem; line-height: 1.2; }
.noticia-meta { display: flex; gap: 16px; font-size: 0.9rem; opacity: 0.9; }
.noticia-meta .cat { font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.noticia-content { max-width: 900px; margin: 40px auto; padding: 0 20px; }
.noticia-image { margin: 0 0 40px; max-width: 100%; height: auto; max-height: 400px; border-radius: var(--radius-lg); object-fit: cover; }
.noticia-body { font-size: 1rem; line-height: 1.7; color: var(--ink); }
.noticia-body p { margin-bottom: 16px; }
.noticia-body p:last-child { margin-bottom: 0; }
</style>
@endsection

@section('content')
<section class="noticia-hero">
  <div class="wrap">
    <div class="crumbs" style="color: rgba(255,255,255,0.7);">
      <a href="/" style="color: rgba(255,255,255,0.9);">Início</a><span class="sep">/</span>
      <a href="{{ route('noticias.index') }}" style="color: rgba(255,255,255,0.9);">Notícias</a><span class="sep">/</span>
      <span>{{ $noticia->titulo }}</span>
    </div>
    <h1>{{ $noticia->titulo }}</h1>
    <div class="noticia-meta">
      <span class="cat">{{ $noticia->categoria }}</span>
      <span>{{ $noticia->data->format('d \\d\\e M \\d\\e Y') }}</span>
    </div>
  </div>
</section>

<section class="section">
  <article class="noticia-content">
    @if ($noticia->imagem)
      <img src="{{ asset('storage/' . $noticia->imagem) }}" alt="{{ $noticia->titulo }}" class="noticia-image">
    @endif

    <div class="noticia-body">
      {!! nl2br(e($noticia->conteudo)) !!}
    </div>
  </article>
</section>
@endsection
