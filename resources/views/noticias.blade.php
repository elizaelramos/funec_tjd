@extends('layouts.app')

@section('title', 'Notícias e Comunicados — TJD · FUNEC')

@section('head')
<style>
.feature{display:grid;grid-template-columns:1.1fr 1fr;gap:0;background:var(--surface);border:1px solid var(--line);
  border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:34px;}
.feature .ph{border:0;border-radius:0;min-height:280px;}
.feature .fc{padding:34px;display:flex;flex-direction:column;justify-content:center;}
.feature .fc .badge{align-self:flex-start;margin-bottom:14px;}
.feature .fc h2{font-size:1.7rem;line-height:1.2;}
.feature .fc p{color:var(--ink-soft);margin-top:12px;line-height:1.62;}
.feature .fc .date{font-family:var(--mono);font-size:.78rem;color:var(--gold-deep);margin-top:16px;}

.news-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
.news{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);overflow:hidden;
  display:flex;flex-direction:column;transition:transform .15s, box-shadow .2s;}
.news:hover{transform:translateY(-3px);box-shadow:var(--shadow);}
.news .ph{border:0;border-radius:0;height:140px;}
.news .nc{padding:20px;display:flex;flex-direction:column;gap:8px;flex:1;}
.news .cat{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-deep);}
.news h3{font-size:1.08rem;line-height:1.3;}
.news .date{font-family:var(--mono);font-size:.74rem;color:var(--muted);margin-top:auto;}
@media (max-width:880px){ .feature{grid-template-columns:1fr;} .news-grid{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Notícias">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Notícias e Comunicados</span></div>
    <h1>Notícias e Comunicados</h1>
    <p class="lede">Comunicados oficiais, avisos de pauta e informações institucionais do Tribunal de Justiça Desportiva da FUNEC.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if ($noticias->isNotEmpty())
      @php $primeira = $noticias->first(); @endphp
      <article class="feature">
        @if ($primeira->imagem)
          <img src="{{ asset('storage/' . $primeira->imagem) }}" alt="{{ $primeira->titulo }}" class="ph" style="object-fit: cover;">
        @else
          <div class="ph">imagem</div>
        @endif
        <div class="fc">
          <span class="badge badge-agendada"><span class="tick"></span>{{ $primeira->categoria }}</span>
          <h2>{{ $primeira->titulo }}</h2>
          <p>{{ $primeira->resumo ?: $primeira->conteudo }}</p>
          <span class="date">{{ $primeira->data->format('d \\d\\e M \\d\\e Y') }}</span>
        </div>
      </article>

      @if ($noticias->count() > 1)
      <div class="news-grid">
        @foreach ($noticias->skip(1) as $noticia)
        <a class="news" href="{{ $noticia->link_externo ?: route('noticias.show', $noticia) }}" {{ $noticia->link_externo ? 'target="_blank"' : '' }}>
          @if ($noticia->imagem)
            <img src="{{ asset('storage/' . $noticia->imagem) }}" alt="{{ $noticia->titulo }}" class="ph" style="object-fit: cover;">
          @else
            <div class="ph">imagem</div>
          @endif
          <div class="nc">
            <span class="cat">{{ $noticia->categoria }}</span>
            <h3>{{ $noticia->titulo }}</h3>
            <span class="date">{{ $noticia->data->format('d M Y') }}</span>
          </div>
        </a>
        @endforeach
      </div>
      @endif
    @else
      <div style="background: var(--surface); padding: 20px; border-radius: var(--radius); color: var(--muted); text-align: center;">
        Nenhuma notícia publicada no momento.
      </div>
    @endif
  </div>
</section>
@endsection
