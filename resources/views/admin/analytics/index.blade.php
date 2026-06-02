@extends('layouts.admin')

@section('title', 'Analytics & Segurança — TJD · FUNEC')

@section('head')
<style>
  .filtros{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:22px;}
  .filtros a{padding:7px 16px;border:1px solid var(--line);border-radius:999px;font-size:.85rem;
    font-weight:600;color:var(--muted);text-decoration:none;background:var(--surface);transition:all .15s;}
  .filtros a:hover{border-color:var(--navy-600);color:var(--navy-700);}
  .filtros a.on{background:var(--navy-800);color:#fff;border-color:var(--navy-800);}

  .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px;}
  .card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);padding:18px 20px;}
  .card .n{font-size:2rem;font-weight:800;color:var(--navy-800);line-height:1.1;}
  .card .lbl{font-size:.8rem;color:var(--muted);font-weight:600;margin-top:4px;}
  .card.alerta .n{color:var(--danger);}
  .card.warn .n{color:var(--warn);}

  .grid2{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;}
  @media(max-width:820px){.grid2{grid-template-columns:1fr;}}
  .panel{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);padding:20px;}
  .panel h3{margin:0 0 14px;font-size:1.05rem;color:var(--navy-800);}
  .panel-full{margin-bottom:28px;}

  table.data td.num{text-align:right;font-variant-numeric:tabular-nums;font-weight:700;color:var(--navy-700);}
  .bar{display:block;height:6px;border-radius:4px;background:var(--navy-600);margin-top:5px;}
  code.payload{font-family:var(--mono);font-size:.78rem;background:#fdecea;color:#b3261e;padding:2px 6px;
    border-radius:4px;word-break:break-all;display:inline-block;max-width:100%;}
  .tag{display:inline-block;padding:2px 9px;border-radius:999px;font-size:.72rem;font-weight:700;}
  .tag.high{background:#fdecea;color:#b3261e;}
  .tag.medium{background:#fff4e0;color:#9a6700;}
  .tag.low{background:#e8f3ec;color:#2f7d51;}
  .mono{font-family:var(--mono);font-size:.82rem;}
  .empty{background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius-lg);
    padding:30px;text-align:center;color:var(--muted);}
  .chart-box{position:relative;height:280px;}
  .nota{font-size:.82rem;color:var(--muted);background:var(--surface-2);border:1px solid var(--line-soft);
    border-radius:var(--radius);padding:12px 16px;margin-bottom:24px;}
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Analytics & Segurança">
  <div class="wrap">
    <div class="crumbs"><a href="/">Início</a><span class="sep">/</span><span>Analytics & Segurança</span></div>
    <h1>Analytics & Segurança</h1>
    <p class="lede">Monitoramento de acessos, buscas e tentativas de intrusão no portal do TJD · FUNEC.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">

    <div class="filtros">
      @foreach ($periodos as $p)
        <a href="{{ route('admin.analytics.index', ['periodo' => $p]) }}" class="{{ $periodo === $p ? 'on' : '' }}">Últimos {{ $p }} dias</a>
      @endforeach
    </div>

    {{-- Cartões de resumo --}}
    <div class="cards">
      <div class="card"><div class="n">{{ number_format($totalAcessos, 0, ',', '.') }}</div><div class="lbl">Acessos totais</div></div>
      <div class="card"><div class="n">{{ number_format($visitantesUnicos, 0, ',', '.') }}</div><div class="lbl">Visitantes únicos (IPs)</div></div>
      <div class="card warn"><div class="n">{{ number_format($acessosBots, 0, ',', '.') }}</div><div class="lbl">Acessos de bots</div></div>
      <div class="card warn"><div class="n">{{ number_format($falhasLogin, 0, ',', '.') }}</div><div class="lbl">Falhas de login</div></div>
      <div class="card alerta"><div class="n">{{ number_format($alertasSeguranca, 0, ',', '.') }}</div><div class="lbl">Alertas de segurança</div></div>
    </div>

    {{-- Gráfico de acessos por dia --}}
    <div class="panel panel-full">
      <h3>Acessos por dia</h3>
      <div class="chart-box"><canvas id="chartAcessos"></canvas></div>
    </div>

    <div class="grid2">
      {{-- Páginas mais acessadas --}}
      <div class="panel">
        <h3>Páginas mais acessadas</h3>
        @if ($topPaginas->isEmpty())
          <div class="empty">Sem dados no período.</div>
        @else
          @php $maxPag = $topPaginas->max('total') ?: 1; @endphp
          <div class="table-wrap"><table class="data">
            <thead><tr><th>Página</th><th style="text-align:right;">Acessos</th></tr></thead>
            <tbody>
              @foreach ($topPaginas as $pag)
                <tr>
                  <td class="mono">{{ $pag->path }}<span class="bar" style="width:{{ round($pag->total / $maxPag * 100) }}%"></span></td>
                  <td class="num">{{ number_format($pag->total, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table></div>
        @endif
      </div>

      {{-- Usuários mais ativos --}}
      <div class="panel">
        <h3>Usuários autenticados mais ativos</h3>
        @if ($topUsuarios->isEmpty())
          <div class="empty">Nenhum acesso autenticado no período.</div>
        @else
          <div class="table-wrap"><table class="data">
            <thead><tr><th>Usuário</th><th style="text-align:right;">Acessos</th></tr></thead>
            <tbody>
              @foreach ($topUsuarios as $row)
                <tr>
                  <td>{{ $row->user->name ?? 'Usuário #' . $row->user_id }}<br><span class="mono" style="color:var(--muted);font-size:.78rem;">{{ $row->user->email ?? '' }}</span></td>
                  <td class="num">{{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table></div>
        @endif
      </div>
    </div>

    {{-- Buscas recentes --}}
    <div class="panel panel-full">
      <h3>Buscas recentes (campo de pesquisa)</h3>
      @if ($buscas->isEmpty())
        <div class="empty">Nenhuma busca registrada no período.</div>
      @else
        <div class="table-wrap"><table class="data">
          <thead><tr><th>Termo buscado</th><th>Página</th><th>IP</th><th>Data</th></tr></thead>
          <tbody>
            @foreach ($buscas as $b)
              <tr>
                <td>
                  @if ($b->suspeita)
                    <span class="tag high">⚠ injeção</span> <code class="payload">{{ $b->search_term }}</code>
                  @else
                    {{ $b->search_term }}
                  @endif
                </td>
                <td class="mono">{{ $b->path }}</td>
                <td class="mono">{{ $b->ip }}</td>
                <td>{{ $b->created_at?->format('d/m H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table></div>
      @endif
    </div>

    {{-- Painel de segurança --}}
    <div class="grid2">
      <div class="panel">
        <h3>Eventos de segurança por tipo</h3>
        @if ($eventosPorTipo->isEmpty())
          <div class="empty">Nenhum evento no período. 🎉</div>
        @else
          <div class="table-wrap"><table class="data">
            <thead><tr><th>Tipo</th><th style="text-align:right;">Total</th></tr></thead>
            <tbody>
              @foreach ($eventosPorTipo as $tipo => $total)
                <tr>
                  <td>{{ \App\Models\SecurityEvent::LABELS[$tipo] ?? $tipo }}</td>
                  <td class="num">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table></div>
        @endif
      </div>

      <div class="panel">
        <h3>IPs com mais atividade suspeita</h3>
        @if ($ipsAtacantes->isEmpty())
          <div class="empty">Nenhum IP suspeito no período.</div>
        @else
          <div class="table-wrap"><table class="data">
            <thead><tr><th>IP</th><th style="text-align:right;">Eventos</th></tr></thead>
            <tbody>
              @foreach ($ipsAtacantes as $row)
                <tr><td class="mono">{{ $row->ip }}</td><td class="num">{{ number_format($row->total, 0, ',', '.') }}</td></tr>
              @endforeach
            </tbody>
          </table></div>
        @endif
      </div>
    </div>

    {{-- Eventos de segurança recentes --}}
    <div class="panel panel-full">
      <h3>Eventos de segurança recentes</h3>
      @if ($eventosRecentes->isEmpty())
        <div class="empty">Nenhum evento de segurança registrado no período.</div>
      @else
        <div class="table-wrap"><table class="data">
          <thead><tr><th>Quando</th><th>Tipo</th><th>Severidade</th><th>Origem / detalhe</th></tr></thead>
          <tbody>
            @foreach ($eventosRecentes as $ev)
              <tr>
                <td style="white-space:nowrap;">{{ $ev->created_at?->format('d/m H:i') }}</td>
                <td>{{ $ev->label() }}</td>
                <td><span class="tag {{ $ev->severity }}">{{ ucfirst($ev->severity) }}</span></td>
                <td>
                  <span class="mono">{{ $ev->ip }}</span>
                  @if ($ev->email) · <span class="mono">{{ $ev->email }}</span> @endif
                  @if ($ev->path) · {{ $ev->path }} @endif
                  @if ($ev->payload)<br><code class="payload">{{ $ev->payload }}</code>@endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table></div>
      @endif
    </div>

    <div class="nota">
      <strong>Sobre o monitoramento de intrusão:</strong> esta página detecta padrões de ataque
      (SQL Injection, XSS, sondagem de rotas como <span class="mono">.env</span>/<span class="mono">wp-admin</span> e
      ferramentas como sqlmap/nikto) <em>dentro da aplicação</em>. É uma camada de visibilidade — não substitui
      proteções de rede no servidor (firewall, WAF, fail2ban), recomendadas para bloqueio efetivo.
    </div>

  </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function () {
    const ctx = document.getElementById('chartAcessos');
    if (!ctx) return;
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($serieLabels),
        datasets: [{
          label: 'Acessos',
          data: @json($serieValores),
          borderColor: '#1f3a5f',
          backgroundColor: 'rgba(31,58,95,.12)',
          fill: true,
          tension: .3,
          pointRadius: 2,
          borderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });
  })();
</script>
@endsection
