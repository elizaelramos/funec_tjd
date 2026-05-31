@extends('layouts.app')

@section('title', 'Composição do Tribunal — TJD · FUNEC')

@section('head')
<style>
.intro-note{display:grid;grid-template-columns:auto 1fr auto;gap:22px;align-items:center;
  background:var(--surface);border:1px solid var(--line);border-left:4px solid var(--gold);
  border-radius:var(--radius-lg);padding:22px 26px;box-shadow:var(--shadow-sm);}
.intro-note .seal{width:52px;height:52px;border-radius:50%;background:var(--navy-900);color:var(--gold-bright);
  display:flex;align-items:center;justify-content:center;flex:none;}
.intro-note h3{font-size:1.1rem;}
.intro-note p{color:var(--muted);font-size:.9rem;margin-top:3px;max-width:70ch;}

.org-head{display:flex;align-items:center;gap:16px;margin:0 0 6px;}
.org-head .num{font-family:var(--serif);font-size:1.6rem;font-weight:700;color:var(--gold-deep);
  width:50px;height:50px;border:2px solid var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;flex:none;}
.org-head h2{font-size:1.7rem;}
.org-head .cap{color:var(--muted);font-size:.9rem;}

.member-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:24px;}
.member{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);
  padding:24px 20px;text-align:center;box-shadow:var(--shadow-sm);transition:transform .15s, box-shadow .2s;}
.member:hover{transform:translateY(-3px);box-shadow:var(--shadow);}
.member .av{width:64px;height:64px;border-radius:50%;margin:0 auto 14px;background:linear-gradient(160deg,var(--navy-700),var(--navy-900));
  color:#fff;font-family:var(--sans);font-weight:700;font-size:1.1rem;display:flex;align-items:center;justify-content:center;
  box-shadow:0 0 0 3px var(--surface),0 0 0 4px var(--line);}
.member .nm{font-family:var(--serif);font-size:1.04rem;font-weight:600;line-height:1.25;color:var(--navy-900);}
.member .ro{margin-top:9px;}
.role-tag{display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  padding:4px 11px;border-radius:999px;background:var(--surface-2);border:1px solid var(--line);color:var(--ink-soft);}
.role-pres{background:linear-gradient(180deg,var(--gold-bright),var(--gold));color:var(--navy-950);border-color:var(--gold-deep);}
.role-proc{background:#eaf0fa;border-color:#cdddf4;color:var(--navy-700);}
.role-sec{background:#e8f3ec;border-color:#c5e2d0;color:#2f7d51;}

.member.featured{grid-column:span 2;display:grid;grid-template-columns:auto 1fr;text-align:left;gap:22px;align-items:center;
  border-color:var(--gold-soft);background:linear-gradient(180deg,var(--surface),var(--surface-2));}
.member.featured .av{margin:0;width:78px;height:78px;font-size:1.4rem;box-shadow:0 0 0 3px var(--surface),0 0 0 5px var(--gold);}
.member.featured .nm{font-size:1.35rem;}
.member.featured .lead{color:var(--muted);font-size:.86rem;margin-top:6px;}

@media (max-width:980px){ .member-grid{grid-template-columns:repeat(3,1fr);} }
@media (max-width:760px){
  .member-grid{grid-template-columns:repeat(2,1fr);}
  .member.featured{grid-column:span 2;}
  .intro-note{grid-template-columns:1fr;text-align:left;}
}
@media (max-width:480px){ .member-grid{grid-template-columns:1fr;} .member.featured{grid-template-columns:1fr;text-align:center;} .member.featured .av{margin:0 auto;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Composição">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Composição do Tribunal</span></div>
    <h1>Composição do Tribunal</h1>
    <p class="lede">Membros do Pleno e da Comissão Disciplinar do TJD da FUNEC, nomeados conforme publicação no Diário Oficial do Município.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="intro-note">
      <span class="seal"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M5 3h9l5 5v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M8 13h8M8 17h5"/></svg></span>
      <div>
        <h3>Portaria FUNEC nº 012, de 22 de abril de 2026</h3>
        <p>Dispõe sobre a nomeação dos membros para a composição do Tribunal de Justiça Desportiva da Fundação de Esportes de Corumbá — FUNEC, nos termos da Lei Complementar nº 287/2021 e da Portaria FUNEC nº 004/2021.</p>
      </div>
      <a class="btn btn-ghost" href="#">Ver portaria (PDF)</a>
    </div>
  </div>
</section>

<!-- ============ PLENO ============ -->
<section class="section tight" id="pleno">
  <div class="wrap">
    <div class="org-head">
      <span class="num">I</span>
      <div>
        <h2>Pleno do TJD</h2>
        <span class="cap">Órgão colegiado máximo — instância recursal e julgamento de matérias de sua competência.</span>
      </div>
    </div>
    <hr class="rule" style="margin-top:18px;">
    <div class="member-grid">
      <div class="member featured">
        <span class="av">SR</span>
        <div>
          <div class="nm">Shirley Monterisi Ribeiro</div>
          <div class="ro"><span class="role-tag role-pres">Presidente</span></div>
          <p class="lead">Preside as sessões do Pleno e representa o Tribunal.</p>
        </div>
      </div>
      <div class="member">
        <span class="av">LO</span>
        <div class="nm">Leonardo Cardoso Batista de Oliveira</div>
        <div class="ro"><span class="role-tag role-proc">Procurador</span></div>
      </div>
      <div class="member">
        <span class="av">ER</span>
        <div class="nm">Elizael Ramos</div>
        <div class="ro"><span class="role-tag role-sec">Secretário-Geral</span></div>
      </div>
      <div class="member">
        <span class="av">HJ</span>
        <div class="nm">Heliney de Miranda Junior</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">DR</span>
        <div class="nm">Davi Vital do Rosário</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">WR</span>
        <div class="nm">Wagner Augusto da Silva Rodrigues</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">AS</span>
        <div class="nm">Adriano Firmino Sena</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">JG</span>
        <div class="nm">José Juliano Souza Guerreiro</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
    </div>
  </div>
</section>

<hr class="gold-rule">

<!-- ============ COMISSÃO DISCIPLINAR ============ -->
<section class="section" id="comissao">
  <div class="wrap">
    <div class="org-head">
      <span class="num">II</span>
      <div>
        <h2>Comissão Disciplinar</h2>
        <span class="cap">Primeira instância — processa e julga as infrações disciplinares das competições da FUNEC.</span>
      </div>
    </div>
    <hr class="rule" style="margin-top:18px;">
    <div class="member-grid">
      <div class="member featured">
        <span class="av">JC</span>
        <div>
          <div class="nm">Jeancarlo Cestari</div>
          <div class="ro"><span class="role-tag role-pres">Presidente</span></div>
          <p class="lead">Preside as sessões de julgamento da Comissão Disciplinar.</p>
        </div>
      </div>
      <div class="member">
        <span class="av">WP</span>
        <div class="nm">Wagner Alves Pereira</div>
        <div class="ro"><span class="role-tag role-sec">Secretário</span></div>
      </div>
      <div class="member">
        <span class="av">BM</span>
        <div class="nm">Bruno Felipe Garcia Martinez</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">CS</span>
        <div class="nm">Carlos Henrique de Siqueira</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">JR</span>
        <div class="nm">João Luiz Ribeiro</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">JC</span>
        <div class="nm">Jeferson Rogério Cortez</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">LA</span>
        <div class="nm">Lucas Gonzales de Arruda</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
      <div class="member">
        <span class="av">JM</span>
        <div class="nm">José Menacho</div>
        <div class="ro"><span class="role-tag">Auditor</span></div>
      </div>
    </div>
    <p class="muted" style="margin-top:26px;font-size:.86rem;max-width:80ch;">A designação para compor a Comissão Disciplinar e o Pleno do TJD da FUNEC não implica remuneração aos seus membros, sendo a prestação de serviços considerada serviço público relevante (Art. 4º, Portaria FUNEC nº 012/2026).</p>
  </div>
</section>
@endsection
