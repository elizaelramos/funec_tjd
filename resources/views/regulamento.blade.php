@extends('layouts.app')

@section('title', 'Regulamento e Legislação — TJD · FUNEC')

@section('head')
<style>
.doc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;}
.doc-card{display:grid;grid-template-columns:auto 1fr auto;gap:18px;align-items:center;background:var(--surface);
  border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:22px 24px;
  transition:transform .15s, box-shadow .2s, border-color .2s;}
.doc-card:hover{transform:translateY(-2px);box-shadow:var(--shadow);border-color:var(--gold-soft);}
.doc-card .ic{width:48px;height:56px;border-radius:6px;background:var(--navy-900);position:relative;flex:none;display:flex;align-items:flex-end;justify-content:center;padding-bottom:9px;}
.doc-card .ic::before{content:"";position:absolute;top:0;right:0;width:16px;height:16px;background:var(--navy-700);border-bottom-left-radius:3px;}
.doc-card .ic span{font-family:var(--mono);font-size:.56rem;color:var(--gold-bright);font-weight:600;}
.doc-card h3{font-size:1.08rem;line-height:1.25;}
.doc-card p{font-size:.82rem;color:var(--muted);margin-top:4px;}
.doc-card .dl{color:var(--navy-700);}

.ref-note{font-size:.84rem;color:var(--muted);margin-top:14px;}
table.data td .art{font-family:var(--mono);font-weight:600;color:var(--navy-700);}
@media (max-width:760px){ .doc-grid{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Regulamento">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Regulamento e Legislação</span></div>
    <h1>Regulamento e Legislação</h1>
    <p class="lede">Código Brasileiro de Justiça Desportiva (CBJD), regimento interno do TJD e regulamentos específicos das competições coordenadas pela FUNEC.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head"><div><span class="eyebrow">Documentos</span><h2 style="margin-top:8px;">Normas aplicáveis</h2></div></div>
    <div class="doc-grid">
      <a class="doc-card" href="#">
        <span class="ic"><span>CBJD</span></span>
        <div><h3>Código Brasileiro de Justiça Desportiva</h3><p>Norma nacional que disciplina infrações e penalidades desportivas.</p></div>
        <span class="dl btn btn-ghost">PDF</span>
      </a>
      <a class="doc-card" href="#">
        <span class="ic"><span>RITJD</span></span>
        <div><h3>Regimento Interno do TJD da FUNEC</h3><p>Organização, competências e ritos das sessões de julgamento.</p></div>
        <span class="dl btn btn-ghost">PDF</span>
      </a>
      <a class="doc-card" href="#">
        <span class="ic"><span>RGC</span></span>
        <div><h3>Regulamento Geral das Competições — FUNEC</h3><p>Regras gerais de disputa, inscrições e disciplina das competições municipais.</p></div>
        <span class="dl btn btn-ghost">PDF</span>
      </a>
      <a class="doc-card" href="/composicao">
        <span class="ic"><span>PORT</span></span>
        <div><h3>Portaria FUNEC nº 012/2026</h3><p>Nomeação dos membros do Pleno e da Comissão Disciplinar.</p></div>
        <span class="dl btn btn-ghost">Ver</span>
      </a>
    </div>

    <div class="section-head" style="margin-top:48px;"><div><span class="eyebrow">Referência rápida</span><h2 style="margin-top:8px;">Infrações mais frequentes</h2></div></div>
    <div class="table-wrap">
      <table class="data">
        <thead><tr><th>Artigo</th><th>Infração</th><th>Penalidade prevista</th></tr></thead>
        <tbody>
          <tr><td><span class="art">Art. 254</span></td><td>Praticar jogada violenta</td><td>Suspensão de 1 a 2 partidas</td></tr>
          <tr><td><span class="art">Art. 254-A</span></td><td>Agredir, tentar agredir ou praticar violência física</td><td>Suspensão de 4 a 6 partidas</td></tr>
          <tr><td><span class="art">Art. 258</span></td><td>Praticar conduta contrária à disciplina ou à ética desportiva</td><td>Suspensão de 1 a 6 partidas e/ou multa</td></tr>
          <tr><td><span class="art">Art. 243</span></td><td>Ofender outrem por atos, palavras ou gestos</td><td>Suspensão de 1 a 6 partidas e/ou multa</td></tr>
          <tr><td><span class="art">Art. 250</span></td><td>Provocar tumulto ou incitar conduta violenta</td><td>Suspensão de 2 a 10 partidas</td></tr>
          <tr><td><span class="art">Art. 213</span></td><td>Responsabilidade do clube por conduta da torcida</td><td>Perda de mando de campo e/ou multa</td></tr>
        </tbody>
      </table>
    </div>
    <p class="ref-note">Quadro meramente informativo. As penalidades efetivas observam as dosimetrias e circunstâncias de cada caso, nos termos do CBJD e dos regulamentos específicos.</p>
  </div>
</section>
@endsection
