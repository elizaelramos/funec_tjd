<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NoticiaController extends Controller
{
    public const CATEGORIAS = [
        'Comunicado',
        'Procuradoria',
        'Institucional',
        'Julgamentos',
        'Competições',
        'Legislação',
        'Aviso',
    ];

    public const STATUS = [
        'rascunho' => 'Rascunho',
        'publicada' => 'Publicada',
    ];

    public function index()
    {
        $noticias = Noticia::publicada()->get();

        return view('noticias', [
            'active' => 'noticias',
            'noticias' => $noticias,
        ]);
    }

    public function show(Noticia $noticia)
    {
        if ($noticia->status !== 'publicada') {
            abort(404);
        }

        return view('noticia-detalhe', [
            'active' => 'noticias',
            'noticia' => $noticia,
        ]);
    }

    public function adminIndex()
    {
        return view('admin.noticias.index', [
            'active' => 'noticias',
            'noticias' => Noticia::orderByDesc('data')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('editor');

        return view('admin.noticias.form', [
            'active' => 'noticias',
            'noticia' => new Noticia(),
            'categorias' => self::CATEGORIAS,
            'status_options' => self::STATUS,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('editor');

        $dados = $this->validar($request);

        $noticia = new Noticia($dados);
        $noticia->usuario_id = auth()->id();

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('noticias', $filename, 'public');
            $noticia->imagem = $path;
            $noticia->imagem_original = $file->getClientOriginalName();
        }

        $noticia->save();

        return redirect()
            ->route('admin.noticias.index')
            ->with('ok', "Notícia '{$noticia->titulo}' cadastrada com sucesso.");
    }

    public function edit(Noticia $noticia)
    {
        $this->authorize('editor');

        return view('admin.noticias.form', [
            'active' => 'noticias',
            'noticia' => $noticia,
            'categorias' => self::CATEGORIAS,
            'status_options' => self::STATUS,
        ]);
    }

    public function update(Request $request, Noticia $noticia)
    {
        $this->authorize('editor');

        $dados = $this->validar($request, $noticia->id);

        $noticia->fill($dados);

        if ($request->has('remover_imagem')) {
            if ($noticia->imagem && Storage::disk('public')->exists($noticia->imagem)) {
                Storage::disk('public')->delete($noticia->imagem);
            }
            $noticia->imagem = null;
            $noticia->imagem_original = null;
        }

        if ($request->hasFile('imagem')) {
            if ($noticia->imagem && Storage::disk('public')->exists($noticia->imagem)) {
                Storage::disk('public')->delete($noticia->imagem);
            }

            $file = $request->file('imagem');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('noticias', $filename, 'public');
            $noticia->imagem = $path;
            $noticia->imagem_original = $file->getClientOriginalName();
        }

        $noticia->save();

        return redirect()
            ->route('admin.noticias.index')
            ->with('ok', "Notícia '{$noticia->titulo}' atualizada com sucesso.");
    }

    public function destroy(Noticia $noticia)
    {
        $this->authorize('editor');

        if ($noticia->imagem && Storage::disk('public')->exists($noticia->imagem)) {
            Storage::disk('public')->delete($noticia->imagem);
        }

        $titulo = $noticia->titulo;
        $noticia->delete();

        return redirect()
            ->route('admin.noticias.index')
            ->with('ok', "Notícia '{$titulo}' excluída com sucesso.");
    }

    private function validar(Request $request, ?int $ignorarId = null): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'in:' . implode(',', self::CATEGORIAS)],
            'resumo' => ['nullable', 'string'],
            'conteudo' => ['nullable', 'string'],
            'data' => ['required', 'date'],
            'link_externo' => ['nullable', 'string', 'max:255'],
            'imagem' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:' . implode(',', array_keys(self::STATUS))],
        ]);
    }
}
