<?php

use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\PautaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\NoticiaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — TJD / FUNEC
|--------------------------------------------------------------------------
| Páginas estáticas usam Route::view (retornam a view direto).
| Páginas com dados do banco apontam para métodos do ProcessoController.
| O 'active' marca o item correspondente no menu (layouts/app.blade.php).
*/

// --- Páginas dinâmicas (lêem do banco) ---
Route::get('/processos', [ProcessoController::class, 'index'])->name('processos.index');
Route::get('/decisoes',  [ProcessoController::class, 'decisoes'])->name('decisoes');
Route::get('/punidos',   [ProcessoController::class, 'punidos'])->name('punidos');
// Detalhe pelo número do processo (ex.: 031/2026). O regex permite a barra.
Route::get('/processos/{numero}', [ProcessoController::class, 'show'])
    ->where('numero', '.*')
    ->name('processos.show');
Route::get('/pauta', [PautaController::class, 'index'])->name('pauta.index');
Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{noticia}', [NoticiaController::class, 'show'])->name('noticias.show');

// --- Área restrita (cadastro de processos, pautas e gerencimento de usuários) ---
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('processos',                 [ProcessoController::class, 'adminIndex'])->name('processos.index');
    Route::get('processos/criar',           [ProcessoController::class, 'create'])->name('processos.create');
    Route::post('processos',                [ProcessoController::class, 'store'])->name('processos.store');
    Route::get('processos/{processo}',      [ProcessoController::class, 'adminShow'])->name('processos.show');
    Route::get('processos/{processo}/editar', [ProcessoController::class, 'edit'])->name('processos.edit');
    Route::put('processos/{processo}',      [ProcessoController::class, 'update'])->name('processos.update');
    Route::delete('processos/{processo}',   [ProcessoController::class, 'destroy'])->name('processos.destroy');
    Route::post('processos/{processo}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');

    Route::get('pautas',                 [PautaController::class, 'adminIndex'])->name('pautas.index');
    Route::get('pautas/criar',           [PautaController::class, 'create'])->name('pautas.create');
    Route::post('pautas',                [PautaController::class, 'store'])->name('pautas.store');
    Route::get('pautas/{pauta}',         [PautaController::class, 'show'])->name('pautas.show');
    Route::get('pautas/{pauta}/resultados', [PautaController::class, 'resultados'])->name('pautas.resultados');
    Route::post('pautas/{pauta}/salvar-resultados', [PautaController::class, 'salvarResultados'])->name('pautas.salvarResultados');
    Route::get('pautas/{pauta}/editar',  [PautaController::class, 'edit'])->name('pautas.edit');
    Route::put('pautas/{pauta}',         [PautaController::class, 'update'])->name('pautas.update');
    Route::delete('pautas/{pauta}',      [PautaController::class, 'destroy'])->name('pautas.destroy');
    Route::post('pautas/{pauta}/documentos', [DocumentoController::class, 'storeAta'])->name('atas.store');

    Route::resource('usuarios', UsuarioController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('usuarios');

    Route::get('noticias',                   [NoticiaController::class, 'adminIndex'])->name('noticias.index');
    Route::get('noticias/criar',             [NoticiaController::class, 'create'])->name('noticias.create');
    Route::post('noticias',                  [NoticiaController::class, 'store'])->name('noticias.store');
    Route::get('noticias/{noticia}/editar',  [NoticiaController::class, 'edit'])->name('noticias.edit');
    Route::put('noticias/{noticia}',         [NoticiaController::class, 'update'])->name('noticias.update');
    Route::delete('noticias/{noticia}',      [NoticiaController::class, 'destroy'])->name('noticias.destroy');
});

// --- Download e visualização de documentos (autenticado) ---
Route::get('documentos/{documento}/view', [DocumentoController::class, 'view'])
    ->middleware('auth')
    ->name('documentos.view');
Route::get('documentos/{documento}/download', [DocumentoController::class, 'download'])
    ->middleware('auth')
    ->name('documentos.download');

// --- Páginas estáticas ---
Route::view('/',            'inicio',      ['active' => 'inicio'])->name('home');
Route::view('/composicao',  'composicao',  ['active' => 'composicao'])->name('composicao');
Route::view('/citacoes',    'citacoes',    ['active' => 'citacoes'])->name('citacoes');
Route::view('/regulamento', 'regulamento', ['active' => 'regulamento'])->name('regulamento');

// --- Autenticação (login, senha, etc.) ---
require __DIR__.'/auth.php';
