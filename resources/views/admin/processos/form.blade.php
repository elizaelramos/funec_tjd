@extends('layouts.admin')

@php
  $editando = $processo->exists;

  // Nível da etapa atual (1 a 4) para a barra de progresso.
  $nivelPorSituacao = [
    'aguardando_citacao' => 1, 'aguardando_agendamento' => 2, 'agendado' => 2,
    'julgado_periodo_recurso' => 3, 'recurso_aceito' => 4, 'julgado' => 4, 'arquivado' => 4,
  ];
  $nivel  = $editando ? ($nivelPorSituacao[$processo->situacao] ?? 1) : 1;
  $etapas = [
    ['chave' => 'processo',   'rotulo' => 'Processo'],
    ['chave' => 'citacao',    'rotulo' => 'Citação'],
    ['chave' => 'julgamento', 'rotulo' => 'Julgamento'],
    ['chave' => 'recurso',    'rotulo' => 'Recurso'],
  ];
@endphp

@section('title', ($editando ? 'Editar' : 'Novo') . ' Processo — TJD · FUNEC')

@section('head')
<style>
.form-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:28px;}
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

/* Barra de progresso por etapas */
.progresso{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:24px 28px;margin-bottom:24px;}
.progresso-topo{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:22px;}
.progresso-topo .titulo{font-size:.95rem;font-weight:700;color:var(--navy-900);}
.progresso-topo .pct{font-size:1.05rem;font-weight:800;color:var(--gold);line-height:1;}
.passos{display:flex;position:relative;}
.passos::before{content:'';position:absolute;top:16px;left:7%;right:7%;height:3px;background:var(--line);z-index:0;}
.passo{flex:1;position:relative;z-index:1;text-align:center;cursor:pointer;background:none;border:none;font:inherit;padding:0;}
.passo .bola{width:34px;height:34px;margin:0 auto 8px;border-radius:50%;background:var(--surface-2);border:3px solid var(--line);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:var(--muted);transition:all .2s;}
.passo .rotulo{font-size:.78rem;font-weight:600;color:var(--muted);}
.passo.concluido .bola{background:var(--gold);border-color:var(--gold);color:var(--navy-900);}
.passo.concluido .rotulo{color:var(--ink);}
.passo.atual .bola{background:var(--navy-900);border-color:var(--navy-900);color:#fff;box-shadow:0 0 0 4px rgba(195,154,63,.2);}
.passo.atual .rotulo{color:var(--navy-900);font-weight:700;}
.passo.aberta .rotulo{text-decoration:underline;text-underline-offset:4px;}

/* Abas */
.abas-nav{display:flex;gap:4px;border-bottom:2px solid var(--line);margin-bottom:24px;flex-wrap:wrap;}
.aba-btn{background:none;border:none;font:inherit;font-weight:600;font-size:.92rem;color:var(--muted);padding:12px 18px;cursor:pointer;border-bottom:3px solid transparent;margin-bottom:-2px;display:flex;align-items:center;gap:8px;}
.aba-btn:hover{color:var(--ink);}
.aba-btn.ativa{color:var(--navy-900);border-bottom-color:var(--gold);}
.aba-btn .cadeado{opacity:.65;font-size:.85rem;}
.aba{display:none;}
.aba.ativa{display:block;}
.bloqueada{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);padding:32px 28px;text-align:center;color:var(--muted);}
.bloqueada .ico{font-size:1.8rem;display:block;margin-bottom:10px;}
.bloqueada h3{margin:0 0 8px;font-size:1.05rem;color:var(--navy-900);}
.bloqueada p{margin:0 auto;max-width:440px;font-size:.9rem;line-height:1.5;}
.bloqueada .voltar{margin-top:16px;background:none;border:none;font:inherit;font-weight:700;color:var(--navy-900);cursor:pointer;text-decoration:underline;}

@media (max-width:680px){ .grid2{grid-template-columns:1fr;} .passo .rotulo{font-size:.68rem;} }
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

    {{-- Barra de progresso --}}
    <div class="progresso">
      <div class="progresso-topo">
        <span class="titulo">Andamento do processo</span>
        <span class="pct">Etapa {{ $nivel }} de {{ count($etapas) }}</span>
      </div>
      <div class="passos">
        @foreach ($etapas as $i => $etapa)
          @php $ordem = $i + 1; @endphp
          <button type="button" class="passo {{ $ordem < $nivel ? 'concluido' : ($ordem == $nivel ? 'atual' : '') }}"
                  data-passo="{{ $etapa['chave'] }}">
            <span class="bola">{{ $ordem < $nivel ? '✓' : $ordem }}</span>
            <span class="rotulo">{{ $etapa['rotulo'] }}</span>
          </button>
        @endforeach
      </div>
    </div>

    {{-- Navegação das abas --}}
    <div class="abas-nav">
      <button type="button" class="aba-btn" data-aba-btn="processo">Processo &amp; Origem</button>
      <button type="button" class="aba-btn" data-aba-btn="citacao">Citação <span class="cadeado">🔒</span></button>
      <button type="button" class="aba-btn" data-aba-btn="julgamento">Julgamento <span class="cadeado">🔒</span></button>
      <button type="button" class="aba-btn" data-aba-btn="recurso">Recurso <span class="cadeado">🔒</span></button>
    </div>

    {{-- ============================================================ --}}
    {{-- ABA 1 · PROCESSO & ORIGEM (formulário)                       --}}
    {{-- ============================================================ --}}
    <div class="aba" data-aba="processo">
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
        <h3 style="font-size: 1rem; margin-bottom: 18px;">Origem do Processo</h3>

        {{-- Origem (apenas criação) --}}
        @if (!$editando)
        <h4 style="font-size: 0.9rem; color: var(--muted); margin-bottom: 14px;">Documento que originou o processo (Opcional)</h4>

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
        @else
        <p style="font-size: 0.85rem; color: var(--muted);">Nenhuma origem registrada. Você pode gerenciar a origem na página do processo.</p>
        @endif
        @endif

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar processo' }}</button>
          <a class="btn btn-ghost" href="{{ route('admin.processos.index') }}">Cancelar</a>
        </div>
      </form>
    </div>

    {{-- ============================================================ --}}
    {{-- ABAS 2-4 · ainda não disponíveis no cadastro                 --}}
    {{-- ============================================================ --}}
    @php
      $bloqueios = [
        'citacao'    => ['Citação',    'do PDF de citação'],
        'julgamento' => ['Julgamento', 'da data, resultado e ata de julgamento'],
        'recurso'    => ['Recurso',    'do recurso e da decisão do recurso'],
      ];
    @endphp
    @foreach ($bloqueios as $chave => [$titulo, $oque])
    <div class="aba" data-aba="{{ $chave }}">
      <div class="bloqueada">
        <span class="ico">🔒</span>
        <h3>{{ $titulo }}</h3>
        @if ($editando)
          <p>O cadastro {{ $oque }} é feito na página do processo, na aba correspondente.</p>
          <a class="btn btn-primary" style="margin-top:16px;display:inline-block;" href="{{ route('admin.processos.show', $processo) }}#{{ $chave }}">Abrir página do processo →</a>
        @else
          <p>Esta etapa fica disponível <strong>depois de cadastrar o processo</strong>. Preencha a aba <strong>Processo &amp; Origem</strong> e clique em <em>Cadastrar processo</em>; em seguida você poderá anexar {{ $oque }} aqui.</p>
          <button type="button" class="voltar" data-voltar>← Voltar para Processo &amp; Origem</button>
        @endif
      </div>
    </div>
    @endforeach

  </div>
</section>
@endsection

@section('scripts')
<script>
  (function () {
    var validas = ['processo', 'citacao', 'julgamento', 'recurso'];

    function ativarAba(chave) {
      if (validas.indexOf(chave) === -1) chave = 'processo';
      document.querySelectorAll('[data-aba]').forEach(function (p) {
        p.classList.toggle('ativa', p.dataset.aba === chave);
      });
      document.querySelectorAll('[data-aba-btn]').forEach(function (b) {
        b.classList.toggle('ativa', b.dataset.abaBtn === chave);
      });
      document.querySelectorAll('[data-passo]').forEach(function (s) {
        s.classList.toggle('aberta', s.dataset.passo === chave);
      });
    }

    document.querySelectorAll('[data-aba-btn]').forEach(function (b) {
      b.addEventListener('click', function () { ativarAba(b.dataset.abaBtn); });
    });
    document.querySelectorAll('[data-passo]').forEach(function (s) {
      s.addEventListener('click', function () { ativarAba(s.dataset.passo); });
    });
    document.querySelectorAll('[data-voltar]').forEach(function (b) {
      b.addEventListener('click', function () { ativarAba('processo'); });
    });

    ativarAba('processo');
  })();
</script>
@endsection
