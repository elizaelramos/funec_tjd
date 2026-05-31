<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public const CARGOS = [
        'secretaria' => 'Secretaria',
        'procurador' => 'Procurador',
        'auditor'    => 'Auditor',
        'outro'      => 'Outro',
    ];

    public const NIVEIS = [
        'visitante'   => 'Visitante',
        'editor'      => 'Editor',
        'admin'       => 'Admin',
        'super_admin' => 'Super Admin',
    ];

    public function index()
    {
        $this->authorize('admin');

        return view('admin.usuarios.index', [
            'usuarios' => User::latest()->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return view('admin.usuarios.form', [
            'usuario' => new User(),
            'cargos'  => self::CARGOS,
            'niveis'  => self::NIVEIS,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $dados = $this->validar($request);

        if ($dados['level'] === 'super_admin' && !Auth::user()->isAtLeast('super_admin')) {
            abort(403, 'Apenas Super Admin pode criar outros Super Admins.');
        }

        $usuario = User::create($dados);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', "Usuário {$usuario->name} cadastrado com sucesso.");
    }

    public function edit(User $usuario)
    {
        $this->authorize('admin');

        if ($usuario->isAtLeast('super_admin') && !Auth::user()->isAtLeast('super_admin')) {
            abort(403, 'Você não tem permissão para editar Super Admins.');
        }

        return view('admin.usuarios.form', [
            'usuario' => $usuario,
            'cargos'  => self::CARGOS,
            'niveis'  => self::NIVEIS,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $this->authorize('admin');

        if ($usuario->isAtLeast('super_admin') && !Auth::user()->isAtLeast('super_admin')) {
            abort(403, 'Você não tem permissão para editar Super Admins.');
        }

        $dados = $this->validar($request, $usuario->id);

        if ($dados['level'] === 'super_admin' && !Auth::user()->isAtLeast('super_admin')) {
            abort(403, 'Apenas Super Admin pode atribuir o nível Super Admin.');
        }

        if (isset($dados['password']) && $dados['password'] === null) {
            unset($dados['password']);
        }

        $usuario->update($dados);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', "Usuário {$usuario->name} atualizado.");
    }

    public function destroy(User $usuario)
    {
        $this->authorize('admin');

        if (Auth::id() === $usuario->id) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('erro', "Você não pode deletar sua própria conta.");
        }

        if ($usuario->isAtLeast('super_admin') && !Auth::user()->isAtLeast('super_admin')) {
            abort(403, 'Você não tem permissão para deletar Super Admins.');
        }

        $nome = $usuario->name;
        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('ok', "Usuário {$nome} excluído.");
    }

    private function validar(Request $request, ?int $ignorarId = null): array
    {
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($ignorarId)],
            'cargo'     => ['nullable', Rule::in(array_keys(self::CARGOS))],
            'telefone'  => ['nullable', 'string', 'max:30'],
            'level'     => ['required', Rule::in(array_keys(self::NIVEIS))],
        ];

        if ($ignorarId === null) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $request->validate($rules, [], [
            'password_confirmation' => 'confirmação de senha',
        ]);
    }
}
