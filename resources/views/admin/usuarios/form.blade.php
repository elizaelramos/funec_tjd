@extends('layouts.admin')

@php $editando = $usuario->exists; @endphp

@section('title', ($editando ? 'Editar' : 'Novo') . ' Usuário — TJD · FUNEC')

@section('head')
<style>
.form-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:28px;max-width:880px;}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.field{display:flex;flex-direction:column;gap:6px;margin-bottom:18px;}
.field label{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);}
.field label .req{color:var(--danger);}
.field input, .field select{font-family:inherit;font-size:.95rem;padding:11px 14px;border:1px solid var(--line);border-radius:var(--radius);background:var(--surface-2);color:var(--ink);width:100%;}
.field input:focus, .field select:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(195,154,63,.15);}
.field .err{color:var(--danger);font-size:.8rem;font-weight:600;}
.field.has-error input, .field.has-error select{border-color:var(--danger);}
.form-actions{display:flex;gap:12px;margin-top:8px;}
.errors-box{background:#fdecea;border:1px solid #f5c2bb;color:#b3261e;border-radius:var(--radius);padding:14px 18px;margin-bottom:22px;font-size:.9rem;}
.errors-box ul{margin:6px 0 0;padding-left:20px;}
.password-note{font-size:.8rem;color:var(--muted);margin-top:4px;font-style:italic;}
@media (max-width:680px){ .grid2{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
<section class="page-hero" data-screen-label="Formulário de Usuário">
  <div class="wrap">
    <div class="crumbs">
      <a href="/">Início</a><span class="sep">/</span>
      <a href="{{ route('admin.usuarios.index') }}">Gestão de Usuários</a><span class="sep">/</span>
      <span>{{ $editando ? 'Editar ' . $usuario->name : 'Novo usuário' }}</span>
    </div>
    <h1>{{ $editando ? 'Editar usuário' : 'Novo usuário' }}</h1>
  </div>
</section>

<section class="section">
  <div class="wrap">
    @if ($errors->any())
      <div class="errors-box">
        Corrija os campos abaixo:
        <ul>
          @foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form class="form-card" method="POST"
          action="{{ $editando ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}">
      @csrf
      @if ($editando) @method('PUT') @endif

      <div class="grid2">
        <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
          <label>Nome <span class="req">*</span></label>
          <input type="text" name="name" value="{{ old('name', $usuario->name) }}" placeholder="João Silva">
          @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('cargo') ? 'has-error' : '' }}">
          <label>Cargo</label>
          <select name="cargo">
            <option value="">— Selecione —</option>
            @foreach ($cargos as $valor => $rotulo)
              <option value="{{ $valor }}" @selected(old('cargo', $usuario->cargo) === $valor)>{{ $rotulo }}</option>
            @endforeach
          </select>
          @error('cargo')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('level') ? 'has-error' : '' }}">
          <label>Nível de acesso <span class="req">*</span></label>
          <select name="level">
            @foreach ($niveis as $valor => $rotulo)
              @if ($valor === 'super_admin' && !Auth::user()->isAtLeast('super_admin'))
                <option value="{{ $valor }}" @selected(old('level', $usuario->level) === $valor) disabled>{{ $rotulo }} (restrito)</option>
              @else
                <option value="{{ $valor }}" @selected(old('level', $usuario->level) === $valor)>{{ $rotulo }}</option>
              @endif
            @endforeach
          </select>
          @error('level')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
          <label>E-mail <span class="req">*</span></label>
          <input type="email" name="email" value="{{ old('email', $usuario->email) }}" placeholder="usuario@tjdfunec.com.br">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('telefone') ? 'has-error' : '' }}">
          <label>Telefone</label>
          <input type="tel" name="telefone" value="{{ old('telefone', $usuario->telefone) }}" placeholder="(67) 99999-9999">
          @error('telefone')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="grid2">
        <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
          <label>Senha <span class="req">*</span></label>
          <input type="password" name="password" placeholder="••••••••">
          @if ($editando)
            <span class="password-note">Deixe em branco para manter a senha atual</span>
          @endif
          @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
          <label>Confirmar senha <span class="req">*</span></label>
          <input type="password" name="password_confirmation" placeholder="••••••••">
          @error('password_confirmation')<span class="err">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar usuário' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.usuarios.index') }}">Cancelar</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:auto;">
          @csrf
          <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
      </div>
    </form>
  </div>
</section>
@endsection
