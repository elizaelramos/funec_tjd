<div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;flex-wrap:wrap;">
    <span class="eyebrow">{{ $processos->count() ?? 0 }} processo(s) cadastrado(s)</span>
    <div style="display:flex;gap:12px;">
        <a class="btn btn-primary" href="{{ route('admin.processos.create') }}">+ Novo processo</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="btn btn-ghost" type="submit">Sair</button>
        </form>
    </div>
</div>
