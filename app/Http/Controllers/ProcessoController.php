<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcessoController extends Controller
{
    /** Opções fixas usadas nos formulários e na validação. */
    public const SITUACOES = [
        'aguardando_citacao'       => 'Aguardando Citação',
        'aguardando_agendamento'   => 'Aguardando Agendamento',
        'agendado'                 => 'Agendado',
        'julgado_periodo_recurso'  => 'Julgado - Período de Recurso',
        'recurso_aceito'           => 'Recurso Aceito',
        'julgado'                  => 'Julgado',
        'arquivado'                => 'Arquivado',
    ];

    // =====================================================================
    //  ÁREA PÚBLICA (consulta)
    // =====================================================================

    /**
     * Página /processos — busca e consulta.
     * Se vier ?q= na URL, filtra por número, clube, competição, etc.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $consulta = Processo::query()->latest();

        if ($q !== '') {
            $consulta->where(function ($busca) use ($q) {
                $busca->where('numero', 'like', "%{$q}%")
                      ->orWhere('clube', 'like', "%{$q}%")
                      ->orWhere('competicao', 'like', "%{$q}%")
                      ->orWhere('denunciado', 'like', "%{$q}%")
                      ->orWhere('partida', 'like', "%{$q}%");
            });
        }

        return view('processos', [
            'active'    => 'processos',
            'q'         => $q,
            'processos' => $consulta->get(),
        ]);
    }

    /**
     * Detalhe público — /processos/{numero} (ex.: 031/2026).
     */
    public function show(string $numero)
    {
        $processo = Processo::with('andamentos', 'documentos', 'pautas.documentos')
            ->where('numero', $numero)
            ->firstOrFail();

        return view('processo-detalhe', [
            'active'   => 'processos',
            'processo' => $processo,
        ]);
    }

    /**
     * Página /decisoes — processos julgados ou em fase de recurso.
     */
    public function decisoes()
    {
        $processos = Processo::whereIn('situacao', ['julgado_periodo_recurso', 'recurso_aceito', 'julgado'])
            ->with('pautas')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($p) {
                $p->prazo_recurso = $this->calcularPrazoRecurso($p);
                return $p;
            });

        return view('decisoes', [
            'active'   => 'decisoes',
            'decisoes' => $processos,
        ]);
    }

    /**
     * Página /punidos — processos com resultado (punição).
     */
    public function punidos()
    {
        $processos = Processo::whereIn('situacao', ['julgado_periodo_recurso', 'recurso_aceito', 'julgado'])
            ->whereNotNull('resultado')
            ->with('pautas')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($p) {
                $p->prazo_recurso = $this->calcularPrazoRecurso($p);
                return $p;
            });

        return view('punidos', [
            'active'   => 'punidos',
            'punidos'  => $processos,
        ]);
    }

    /**
     * Calcula o prazo de recurso (3 dias corridos = 72 horas a partir do julgamento).
     * Retorna array com: data_limite, horas_restantes, em_prazo (bool)
     */
    private function calcularPrazoRecurso(Processo $processo): array
    {
        // Obter a data do julgamento da pauta relacionada
        $pauta = $processo->pautas()
            ->where('situacao', 'julgada')
            ->latest('data')
            ->first();

        if (!$pauta) {
            return [
                'data_limite' => null,
                'horas_restantes' => null,
                'em_prazo' => false,
            ];
        }

        // 3 dias corridos = 72 horas
        $data_julgamento = $pauta->data;
        $data_limite = $data_julgamento->copy()->addHours(72);

        $agora = now();
        $horas_restantes = null;
        $em_prazo = false;

        if ($agora <= $data_limite) {
            $em_prazo = true;
            $horas_restantes = (int) $agora->diffInHours($data_limite);
        }

        return [
            'data_limite' => $data_limite,
            'horas_restantes' => $horas_restantes,
            'em_prazo' => $em_prazo,
        ];
    }

    // =====================================================================
    //  ÁREA RESTRITA (cadastro) — TODO: proteger com autenticação (item 1)
    // =====================================================================

    /** Lista de processos para gestão (/admin/processos). */
    public function adminIndex()
    {
        // Qualquer usuário autenticado pode visualizar
        return view('admin.processos.index', [
            'active'    => 'processos',
            'processos' => Processo::latest()->get(),
        ]);
    }

    /** Detalhe admin — /admin/processos/{processo} */
    public function adminShow(Processo $processo)
    {
        $this->authorize('editor');

        return view('admin.processos.show', [
            'active'      => 'processos',
            'processo'    => $processo->load('documentos', 'pautas'),
            'situacoes'   => self::SITUACOES,
            'tiposDoc'    => ['origem', 'citacao', 'recurso', 'decisao_recurso'],
        ]);
    }

    /** Formulário de novo processo. */
    public function create()
    {
        $this->authorize('editor');

        return view('admin.processos.form', [
            'active'    => 'processos',
            'processo'  => new Processo(),
            'situacoes' => self::SITUACOES,
        ]);
    }

    /** Salva um novo processo. */
    public function store(Request $request)
    {
        $this->authorize('editor');

        $dados = $this->validar($request);
        $dados['situacao'] = 'aguardando_citacao';
        $processo = Processo::create($dados);

        // Salvar origem se fornecida
        if ($request->filled('origem_titulo') || $request->hasFile('origem_arquivo')) {
            $doc = new \App\Models\Documento([
                'tipo'       => 'origem',
                'titulo'     => $request->origem_titulo ?: 'Origem do processo',
                'descricao'  => $request->origem_descricao,
                'usuario_id' => auth()->id(),
            ]);

            if ($request->hasFile('origem_arquivo')) {
                $file = $request->file('origem_arquivo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documentos', $filename, 'public');
                $doc->arquivo = $path;
                $doc->nome_original = $file->getClientOriginalName();
            }

            $processo->documentos()->save($doc);
        }

        return redirect()
            ->route('admin.processos.index')
            ->with('ok', "Processo {$processo->numero} cadastrado com sucesso.");
    }

    /** Formulário de edição. */
    public function edit(Processo $processo)
    {
        $this->authorize('editor');

        return view('admin.processos.form', [
            'active'    => 'processos',
            'processo'  => $processo,
            'situacoes' => self::SITUACOES,
        ]);
    }

    /** Atualiza um processo existente. */
    public function update(Request $request, Processo $processo)
    {
        $this->authorize('editor');

        $dados = $this->validar($request, $processo->id);
        $processo->update($dados);

        // Processar citação se fornecida
        if ($request->hasFile('citacao_arquivo')) {
            $file = $request->file('citacao_arquivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documentos', $filename, 'public');

            // Deletar citação anterior se existir
            $citacaoAntiga = $processo->documentos()->where('tipo', 'citacao')->first();
            if ($citacaoAntiga && $citacaoAntiga->arquivo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($citacaoAntiga->arquivo);
                $citacaoAntiga->delete();
            }

            // Criar nova citação
            \App\Models\Documento::create([
                'processo_id' => $processo->id,
                'tipo' => 'citacao',
                'titulo' => 'Citação do Procurador',
                'arquivo' => $path,
                'nome_original' => $file->getClientOriginalName(),
                'usuario_id' => auth()->id(),
            ]);

            // Mudar status para aguardando_agendamento
            $processo->update(['situacao' => 'aguardando_agendamento']);
        }

        return redirect()
            ->route('admin.processos.index')
            ->with('ok', "Processo {$processo->numero} atualizado.");
    }

    /** Exclui um processo (e seus andamentos, via cascade). */
    public function destroy(Processo $processo)
    {
        $this->authorize('editor');

        $numero = $processo->numero;
        $processo->delete();

        return redirect()
            ->route('admin.processos.index')
            ->with('ok', "Processo {$numero} excluído.");
    }

    /**
     * Regras de validação compartilhadas por store() e update().
     * $ignorarId permite que, ao editar, o próprio registro não dispare
     * o erro de "número já existe".
     */
    private function validar(Request $request, ?int $ignorarId = null): array
    {
        return $request->validate([
            'numero'          => ['required', 'string', 'max:20', Rule::unique('processos', 'numero')->ignore($ignorarId)],
            'assunto'         => ['required', 'string', 'max:255'],
            'competicao'      => ['required', 'string', 'max:255'],
            'situacao'        => ['nullable', Rule::in(array_keys(self::SITUACOES))],
            'relator'         => ['nullable', 'string', 'max:255'],
            'enquadramento'   => ['nullable', 'string', 'max:255'],
            'denunciante'     => ['nullable', 'string', 'max:255'],
            'denunciado'      => ['nullable', 'string', 'max:255'],
            'partida'         => ['nullable', 'string', 'max:255'],
            'clube'           => ['nullable', 'string', 'max:255'],
            'resultado'       => ['nullable', 'string', 'max:255'],
            'data_julgamento' => ['nullable', 'date'],
            'origem_titulo'   => ['nullable', 'string', 'max:255'],
            'origem_descricao'=> ['nullable', 'string'],
            'origem_arquivo'  => ['nullable', 'file', 'max:10240'],
            'citacao_arquivo' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [], [
            'data_julgamento'=> 'data de julgamento',
            'origem_arquivo' => 'arquivo de origem',
            'citacao_arquivo'=> 'arquivo de citação',
        ]);
    }
}
