@extends('layouts.app')

@section('title', 'TJD · FUNEC — Tribunal de Justiça Desportiva de Corumbá-MS')

@section('head')
<style>
/* ---------- HOME-SPECIFIC ---------- */
.hero{position:relative;background:var(--navy-900);color:#fff;overflow:hidden;}
.hero::before{content:"";position:absolute;inset:0;
  background:
    radial-gradient(1000px 520px at 82% -20%, rgba(195,154,63,.22), transparent 58%),
    radial-gradient(820px 600px at -8% 118%, rgba(36,76,135,.55), transparent 60%);
}
.hero .wrap{position:relative;display:grid;grid-template-columns:1.25fr .9fr;gap:48px;align-items:center;padding:62px 24px 70px;}
.hero-copy .eyebrow{display:inline-flex;align-items:center;gap:10px;margin-bottom:18px;}
.hero-copy .eyebrow::before{content:"";width:26px;height:1px;background:var(--gold);}
.hero-copy h2{color:#fff;font-size:3rem;line-height:1.06;font-weight:700;letter-spacing:-.02em;}
.hero-copy h2 em{font-style:normal;color:var(--gold-bright);}
.hero-copy .lede{color:#c8d3e6;font-size:1.1rem;margin-top:20px;max-width:52ch;line-height:1.65;}
.hero-cta{display:flex;gap:14px;margin-top:30px;flex-wrap:wrap;}
.hero-emblem{position:relative;display:flex;justify-content:center;}
.hero-emblem img{width:min(330px,80%);filter:drop-shadow(0 30px 60px rgba(0,0,0,.5));}
.hero-emblem::after{content:"";position:absolute;inset:auto 0 -30px;height:60px;
  background:radial-gradient(closest-side, rgba(0,0,0,.4), transparent);}

/* próxima sessão band */
.next-band{background:var(--navy-950);border-top:1px solid rgba(255,255,255,.08);}
.next-band .wrap{display:grid;grid-template-columns:auto 1fr auto;gap:30px;align-items:center;padding:24px;}
.next-band .when{display:flex;align-items:center;gap:18px;}
.next-band .cal{width:74px;height:78px;border-radius:8px;background:var(--surface);color:var(--navy-900);
  display:flex;flex-direction:column;overflow:hidden;border:1px solid var(--gold);box-shadow:var(--shadow);flex:none;}
.next-band .cal .m{background:linear-gradient(180deg,var(--gold-bright),var(--gold));color:var(--navy-950);font-size:.66rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;text-align:center;padding:4px 0;}
.next-band .cal .d{font-family:var(--serif);font-weight:700;font-size:2rem;text-align:center;line-height:1.7;}
.next-band .label{color:var(--gold-bright);font-size:.72rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;}
.next-band .ttl{color:#fff;font-family:var(--serif);font-size:1.25rem;font-weight:600;margin-top:3px;}
.next-band .meta{color:#9fb0cc;font-size:.9rem;margin-top:5px;display:flex;gap:18px;flex-wrap:wrap;}
.next-band .meta b{color:#dbe4f1;font-weight:600;}

/* quick access */
.qa{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
.qa-card{display:flex;flex-direction:column;gap:14px;padding:24px;background:var(--surface);
  border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);
  transition:transform .15s, box-shadow .2s, border-color .2s;position:relative;overflow:hidden;}
.qa-card::after{content:"";position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--gold);transform:scaleY(0);transform-origin:top;transition:transform .2s;}
.qa-card:hover{transform:translateY(-3px);box-shadow:var(--shadow);border-color:var(--gold-soft);}
.qa-card:hover::after{transform:scaleY(1);}
.qa-ico{width:46px;height:46px;border-radius:9px;background:var(--navy-900);color:var(--gold-bright);display:flex;align-items:center;justify-content:center;}
.qa-card h3{font-size:1.12rem;}
.qa-card p{font-size:.88rem;color:var(--muted);line-height:1.55;}
.qa-card .go{margin-top:auto;font-size:.82rem;font-weight:700;color:var(--navy-700);display:flex;align-items:center;gap:6px;}

/* two column */
.split{display:grid;grid-template-columns:1.55fr .95fr;gap:40px;align-items:start;}

/* decision list */
.dec-item{display:flex;gap:18px;padding:18px 0;border-bottom:1px solid var(--line-soft);}
.dec-item:first-child{padding-top:0;}
.dec-item:last-child{border-bottom:0;}
.dec-item .ord{font-family:var(--serif);font-size:1.4rem;color:var(--gold-deep);font-weight:700;width:30px;flex:none;line-height:1.2;}
.dec-item .body h4{font-size:1.04rem;font-weight:600;}
.dec-item .body .info{color:var(--muted);font-size:.86rem;margin-top:3px;}
.dec-item .body .tags{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;}

/* notices */
.notice{display:flex;gap:14px;padding:16px 0;border-bottom:1px solid var(--line-soft);}
.notice:last-child{border-bottom:0;}
.notice .date{font-family:var(--mono);font-size:.74rem;color:var(--gold-deep);font-weight:500;white-space:nowrap;padding-top:2px;}
.notice .t{font-size:.94rem;font-weight:600;color:var(--ink);line-height:1.4;}
.notice:hover .t{color:var(--navy-700);}

/* suspended strip */
.susp{background:var(--surface);border:1px solid var(--line);border-left:4px solid var(--danger);border-radius:var(--radius-lg);padding:22px 26px;}
.susp .row-people{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;}
.person-chip{display:flex;align-items:center;gap:10px;background:var(--surface-2);border:1px solid var(--line-soft);border-radius:999px;padding:6px 14px 6px 6px;}
.person-chip .av{width:30px;height:30px;border-radius:50%;background:var(--navy-800);color:#fff;font-size:.74rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex:none;font-family:var(--sans);}
.person-chip .nm{font-size:.84rem;font-weight:600;}
.person-chip .cl{font-size:.74rem;color:var(--muted);}

@media (max-width:880px){
  .hero .wrap{grid-template-columns:1fr;gap:30px;}
  .hero-emblem{order:-1;}
  .hero-emblem img{width:200px;}
  .hero-copy h2{font-size:2.2rem;}
  .next-band .wrap{grid-template-columns:1fr;gap:18px;}
  .qa{grid-template-columns:repeat(2,1fr);}
  .split{grid-template-columns:1fr;gap:30px;}
}
@media (max-width:560px){ .qa{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<!-- ============ HERO ============ -->
<section class="hero" data-screen-label="Home — Hero">
  <div class="wrap">
    <div class="hero-copy">
      <span class="eyebrow on-dark">Corumbá · Mato Grosso do Sul</span>
      <h2>A justiça que <em>equilibra</em> o esporte de Corumbá.</h2>
      <p class="lede">Portal oficial do Tribunal de Justiça Desportiva da FUNEC. Acompanhe pautas de julgamento, decisões, citações e a relação de punidos das competições municipais — com transparência e acesso público.</p>
      <div class="hero-cta">
        <a class="btn btn-gold" href="/pauta">Ver pauta de julgamentos <span class="arrow">→</span></a>
        <a class="btn btn-ghost on-dark" href="/processos">Consultar processo</a>
      </div>
    </div>
    <div class="hero-emblem">
      <img src="{{ asset('assets/logo-tjd-transparent.png') }}" alt="Brasão do Tribunal de Justiça Desportiva da FUNEC">
    </div>
  </div>
</section>

<!-- ============ PRÓXIMA SESSÃO ============ -->
<section class="next-band">
  <div class="wrap">
    <div class="when">
      <div class="cal"><span class="m">Jun</span><span class="d">04</span></div>
      <div>
        <div class="label">Próxima sessão de julgamento</div>
        <div class="ttl">5ª Sessão — Comissão Disciplinar</div>
        <div class="meta"><span><b>Quinta-feira</b>, 04/06/2026</span><span><b>19h00</b> · Sede da FUNEC</span><span><b>6 processos</b> em pauta</span></div>
      </div>
    </div>
    <div></div>
    <a class="btn btn-ghost on-dark" href="/pauta">Abrir pauta completa <span class="arrow">→</span></a>
  </div>
</section>

<!-- ============ ACESSO RÁPIDO ============ -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow">Acesso rápido</span>
        <h2 style="margin-top:8px;">O Tribunal em um relance</h2>
      </div>
      <p class="sub">Tudo o que é público fica a um clique: composição, pautas, decisões e a relação de punidos por competição.</p>
    </div>
    <div class="qa">
      <a class="qa-card" href="/composicao">
        <span class="qa-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z"/></svg></span>
        <h3>Composição</h3>
        <p>Membros do Pleno e da Comissão Disciplinar, conforme Portaria FUNEC.</p>
        <span class="go">Ver membros →</span>
      </a>
      <a class="qa-card" href="/pauta">
        <span class="qa-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9h18M8 2.5v4M16 2.5v4M7.5 14h4M7.5 17h7"/></svg></span>
        <h3>Pauta de Julgamentos</h3>
        <p>Sessões agendadas, horários e processos a serem julgados.</p>
        <span class="go">Ver sessões →</span>
      </a>
      <a class="qa-card" href="/decisoes">
        <span class="qa-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16M5 8h14M7 8l-3 6a3 3 0 0 0 6 0zM17 8l-3 6a3 3 0 0 0 6 0z"/></svg></span>
        <h3>Decisões</h3>
        <p>Resultados dos julgamentos: punições aplicadas e absolvições.</p>
        <span class="go">Ver decisões →</span>
      </a>
      <a class="qa-card" href="/punidos">
        <span class="qa-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-6 8-6s8 2 8 6"/><path d="M16.5 4.5l3 3"/></svg></span>
        <h3>Punidos / Suspensos</h3>
        <p>Atletas e participantes com punição ativa, por competição.</p>
        <span class="go">Ver relação →</span>
      </a>
    </div>
  </div>
</section>

<hr class="gold-rule">

<!-- ============ ÚLTIMAS DECISÕES + COMUNICADOS ============ -->
<section class="section">
  <div class="wrap split">
    <div>
      <div class="section-head">
        <div><span class="eyebrow">Transparência</span><h2 style="margin-top:8px;">Últimas decisões</h2></div>
        <a class="btn btn-ghost" href="/decisoes">Todas →</a>
      </div>
      <div class="card card-pad">
        <div class="dec-item">
          <span class="ord">01</span>
          <div class="body">
            <h4>Suspensão de 4 partidas por agressão física a adversário</h4>
            <div class="info"><span class="procno">Proc. 031/2026</span> · Operário FC × Corumbaense EC · Campeonato Corumbaense Série A</div>
            <div class="tags"><span class="chip">Art. 254-A, CBJD</span><span class="badge badge-julgada"><span class="tick"></span>Julgado</span></div>
          </div>
        </div>
        <div class="dec-item">
          <span class="ord">02</span>
          <div class="body">
            <h4>Absolvição — insuficiência de provas</h4>
            <div class="info"><span class="procno">Proc. 030/2026</span> · Náutico Corumbá · Copa FUNEC de Futsal</div>
            <div class="tags"><span class="chip">Art. 243-F, CBJD</span><span class="badge badge-julgada"><span class="tick"></span>Julgado</span></div>
          </div>
        </div>
        <div class="dec-item">
          <span class="ord">03</span>
          <div class="body">
            <h4>Multa e suspensão de 2 partidas por conduta antidesportiva</h4>
            <div class="info"><span class="procno">Proc. 029/2026</span> · Pantanal FC · Campeonato Municipal Sub-20</div>
            <div class="tags"><span class="chip">Art. 258, CBJD</span><span class="badge badge-julgada"><span class="tick"></span>Julgado</span></div>
          </div>
        </div>
        <div class="dec-item">
          <span class="ord">04</span>
          <div class="body">
            <h4>Perda de mando de campo por arremesso de objetos</h4>
            <div class="info"><span class="procno">Proc. 027/2026</span> · Ferroviário AC · Campeonato Corumbaense Série A</div>
            <div class="tags"><span class="chip">Art. 213, CBJD</span><span class="badge badge-julgada"><span class="tick"></span>Julgado</span></div>
          </div>
        </div>
      </div>
    </div>

    <aside>
      <div class="section-head"><div><span class="eyebrow">Mural</span><h2 style="margin-top:8px;font-size:1.4rem;">Comunicados</h2></div></div>
      <div class="card card-pad" style="margin-bottom:22px;">
        <a class="notice" href="/noticias"><span class="date">26 MAI</span><span class="t">Publicada a pauta da 5ª Sessão da Comissão Disciplinar</span></a>
        <a class="notice" href="/citacoes"><span class="date">22 MAI</span><span class="t">Novas citações emitidas pela Procuradoria — prazo de defesa de 2 dias úteis</span></a>
        <a class="notice" href="/composicao"><span class="date">22 ABR</span><span class="t">Portaria FUNEC nº 012/2026 — nova composição do TJD</span></a>
        <a class="notice" href="/noticias"><span class="date">15 ABR</span><span class="t">Início do Campeonato Corumbaense de Futebol 2026</span></a>
      </div>
      <div class="susp">
        <span class="eyebrow" style="color:var(--danger);">Atenção</span>
        <h3 style="margin-top:8px;font-size:1.15rem;">Suspensos para a próxima rodada</h3>
        <div class="row-people">
          <span class="person-chip"><span class="av">RS</span><span><span class="nm">R. Santana</span> <span class="cl">· Operário FC</span></span></span>
          <span class="person-chip"><span class="av">LM</span><span><span class="nm">L. Mendes</span> <span class="cl">· Pantanal FC</span></span></span>
          <span class="person-chip"><span class="av">JP</span><span><span class="nm">J. Prado</span> <span class="cl">· Ferroviário AC</span></span></span>
        </div>
        <a class="btn btn-ghost" style="margin-top:16px;" href="/punidos">Relação completa →</a>
      </div>
    </aside>
  </div>
</section>
@endsection
