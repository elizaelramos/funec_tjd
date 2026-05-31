<?php

namespace App\Http\Controllers;

use App\Models\Pauta;
use App\Models\Processo;
use Illuminate\Http\Request;

class PautaController extends Controller
{
    public const ORGAOS = [
        'comissao_disciplinar' => 'Comissão Disciplinar',
        'pleno_tjd'            => 'Pleno do TJD',
    ];

    public const SITUACOES = [
        'agendada' => 'Agendada',
        'julgada'  => 'Julgada',
    ];

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $consulta = Pauta::query()
            ->with('processos')
            ->orderByDesc('data');

        if ($q !== '') {
            $consulta->where(function ($busca) use ($q) {
                $busca->where('numero', 'like', "%{$q}%")
                      ->orWhere('titulo', 'like', "%{$q}%")
                      ->orWhere('orgao_julgador', 'like', "%{$q}%");
            });
        }

        return view('pauta', [
            'active' => 'pauta',
            'pautas' => $consulta->get(),
            'q'      => $q,
        ]);
    }

    public function adminIndex()
    {
        return view('admin.pautas.index', [
            'active' => 'pautas',
            'pautas' => Pauta::with('processos')->latest('data')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('editor');

        return view('admin.pautas.form', [
            'active'     => 'pautas',
            'pauta'      => new Pauta(),
            'orgaos'     => self::ORGAOS,
            'situacoes'  => self::SITUACOES,
            'processos'  => Processo::whereIn('situacao', ['aguardando_agendamento', 'recurso_aceito'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('editor');

        $dados = $this->validar($request);
        $pauta = Pauta::create($dados);

        if ($request->has('processos')) {
            $processoIds = $request->input('processos');
            $this->validarProcessosCitacao($processoIds);
            $pauta->processos()->sync($processoIds);

            // Atualizar status dos processos para agendado
            Processo::whereIn('id', $processoIds)->update(['situacao' => 'agendado']);
        }

        return redirect()
            ->route('admin.pautas.index')
            ->with('ok', "Pauta {$pauta->numero} criada com sucesso.");
    }

    public function show(Pauta $pauta)
    {
        $this->authorize('editor');

        return view('admin.pautas.show', [
            'active'    => 'pautas',
            'pauta'     => $pauta->load('processos', 'documentos'),
            'situacoes' => self::SITUACOES,
        ]);
    }

    public function resultados(Pauta $pauta)
    {
        $this->authorize('editor');

        return view('admin.pautas.resultados', [
            'active' => 'pautas',
            'pauta'  => $pauta->load('processos'),
        ]);
    }

    public function salvarResultados(Request $request, Pauta $pauta)
    {
        $this->authorize('editor');

        $request->validate([
            'ata_arquivo' => 'required|file|mimes:pdf|max:10240',
            'ata_titulo' => 'nullable|string|max:255',
            'resultados' => 'required|array',
            'resultados.*' => 'required|string|max:255',
        ]);

        // Verificar se todos os resultados foram preenchidos
        $processoIds = $pauta->processos->pluck('id')->toArray();
        $resultadosProvidedIds = array_keys($request->resultados);

        if (count(array_diff($processoIds, $resultadosProvidedIds)) > 0) {
            return back()->withErrors(['resultados' => 'Todos os resultados devem ser preenchidos.']);
        }

        // Salvar Ata de Decisão
        $file = $request->file('ata_arquivo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documentos', $filename, 'public');

        \App\Models\Documento::create([
            'pauta_id' => $pauta->id,
            'tipo' => 'ata',
            'titulo' => $request->ata_titulo ?: 'Ata de Julgamento',
            'arquivo' => $path,
            'nome_original' => $file->getClientOriginalName(),
            'usuario_id' => auth()->id(),
        ]);

        // Atualizar resultados e status de cada processo
        foreach ($request->resultados as $processoId => $resultado) {
            Processo::find($processoId)->update([
                'resultado' => $resultado,
                'situacao' => 'julgado_periodo_recurso',
            ]);
        }

        // Atualizar status da pauta para julgada
        $pauta->update(['situacao' => 'julgada']);

        return redirect()->route('admin.pautas.show', $pauta)
            ->with('ok', "Resultados salvos e Ata adicionada com sucesso. Todos os processos foram movidos para período de recurso.");
    }

    public function edit(Pauta $pauta)
    {
        $this->authorize('editor');

        return view('admin.pautas.form', [
            'active'     => 'pautas',
            'pauta'      => $pauta,
            'orgaos'     => self::ORGAOS,
            'situacoes'  => self::SITUACOES,
            'processos'  => Processo::whereIn('situacao', ['aguardando_agendamento', 'recurso_aceito'])->get(),
        ]);
    }

    public function update(Request $request, Pauta $pauta)
    {
        $this->authorize('editor');

        $dados = $this->validar($request, $pauta->id);
        $pauta->update($dados);

        if ($request->has('processos')) {
            $processoIds = $request->input('processos');
            $this->validarProcessosCitacao($processoIds);

            // Obter IDs antigos e novos
            $processosAntigos = $pauta->processos->pluck('id')->toArray();

            // Processos que são adicionados (novos)
            $processosNovos = array_diff($processoIds, $processosAntigos);

            // Atualizar status apenas dos novos para agendado
            if (!empty($processosNovos)) {
                Processo::whereIn('id', $processosNovos)->update(['situacao' => 'agendado']);
            }

            $pauta->processos()->sync($processoIds);
        }

        return redirect()
            ->route('admin.pautas.index')
            ->with('ok', "Pauta {$pauta->numero} atualizada.");
    }

    public function destroy(Pauta $pauta)
    {
        $this->authorize('editor');

        $numero = $pauta->numero;
        $pauta->delete();

        return redirect()
            ->route('admin.pautas.index')
            ->with('ok', "Pauta {$numero} excluída.");
    }

    private function validarProcessosCitacao(array $processoIds): void
    {
        if (empty($processoIds)) {
            return;
        }

        $processosComFalta = Processo::whereIn('id', $processoIds)
            ->where(function ($query) {
                $query->whereDoesntHave('documentos', fn($q) => $q->where('tipo', 'citacao'));
            })
            ->pluck('numero');

        if ($processosComFalta->isNotEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'processos' => [
                    "Os seguintes processos não possuem citação registrada: {$processosComFalta->implode(', ')}. "
                    . "Todas as citações devem estar registradas antes de agendar um processo para julgamento."
                ]
            ]);
        }
    }

    private function validar(Request $request, ?int $ignorarId = null): array
    {
        return $request->validate([
            'numero'           => ['required', 'string', 'max:50'],
            'titulo'           => ['required', 'string', 'max:255'],
            'orgao_julgador'   => ['required', 'in:' . implode(',', array_keys(self::ORGAOS))],
            'data'             => ['required', 'date'],
            'hora'             => ['required', 'date_format:H:i'],
            'local'            => ['nullable', 'string', 'max:255'],
            'situacao'         => ['required', 'in:' . implode(',', array_keys(self::SITUACOES))],
        ]);
    }
}
