<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Processo;
use App\Models\Pauta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function store(Request $request, Processo $processo)
    {
        $this->authorize('editor');

        $request->validate([
            'tipo' => 'required|in:origem,citacao,ata,recurso,decisao_recurso',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|max:10240',
            'data' => 'nullable|date',
            'status_recurso' => 'nullable|in:aceito,negado',
        ]);

        $documento = new Documento([
            'processo_id' => $processo->id,
            'tipo' => $request->tipo,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'usuario_id' => auth()->id(),
            'status_recurso' => $request->status_recurso,
        ]);

        if ($request->hasFile('arquivo')) {
            $file = $request->file('arquivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documentos', $filename, 'public');
            $documento->arquivo = $path;
            $documento->nome_original = $file->getClientOriginalName();
        }

        $documento->save();

        // Transições automáticas de status
        if ($request->tipo === 'citacao') {
            $processo->update(['situacao' => 'aguardando_agendamento']);
        } elseif ($request->tipo === 'decisao_recurso') {
            if ($request->status_recurso === 'aceito') {
                $processo->update(['situacao' => 'recurso_aceito']);
            } elseif ($request->status_recurso === 'negado') {
                $processo->update(['situacao' => 'julgado']);
            }
        }

        return redirect()->route('admin.processos.show', $processo)
            ->with('success', 'Documento adicionado com sucesso.');
    }

    public function storeAta(Request $request, Pauta $pauta)
    {
        $this->authorize('editor');

        $request->validate([
            'titulo' => 'required|string|max:255',
            'arquivo' => 'required|file|max:10240',
        ]);

        $file = $request->file('arquivo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documentos', $filename, 'public');

        $documento = Documento::create([
            'pauta_id' => $pauta->id,
            'tipo' => 'ata',
            'titulo' => $request->titulo,
            'arquivo' => $path,
            'nome_original' => $file->getClientOriginalName(),
            'usuario_id' => auth()->id(),
        ]);

        // Atualizar automaticamente todos os processos para julgado_periodo_recurso
        $pauta->processos()->update(['situacao' => 'julgado_periodo_recurso']);

        return redirect()->route('admin.pautas.show', $pauta)
            ->with('success', 'Ata de Julgamento adicionada com sucesso. Todos os processos foram movidos para período de recurso.');
    }

    public function view(Documento $documento)
    {
        $this->authorize('editor');

        if (!$documento->arquivo) {
            abort(404);
        }

        return Storage::disk('public')->response($documento->arquivo);
    }

    public function download(Documento $documento)
    {
        $this->authorize('editor');

        if (!$documento->arquivo) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $documento->arquivo,
            $documento->nome_original ?? 'documento'
        );
    }

    public function destroy(Documento $documento)
    {
        $this->authorize('editor');

        if ($documento->arquivo && Storage::disk('public')->exists($documento->arquivo)) {
            Storage::disk('public')->delete($documento->arquivo);
        }

        $documento->delete();

        return back()->with('success', 'Documento removido com sucesso.');
    }
}
