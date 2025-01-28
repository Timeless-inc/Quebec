<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/filterJustificativas.js') }}"></script>

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prioridades:
        </h2>
        <button onclick="filterJustificativas('todos')" type="button" class="btn btn-sm btn-secondary filter-btn" data-status="todos">Todos</button>
        <button onclick="filterJustificativas('atencao')" type="button" class="btn btn-sm btn-warning filter-btn" data-status="atencao">Atenção</button>
        <button onclick="filterJustificativas('indeferido')" type="button" class="btn btn-sm btn-danger filter-btn" data-status="indeferido">Indeferido</button>
        <button onclick="filterJustificativas('resolvido')" type="button" class="btn btn-sm btn-success filter-btn" data-status="resolvido">Resolvido</button>
        <button onclick="filterJustificativas('em_andamento')" type="button" class="btn btn-sm btn-info filter-btn" data-status="em_andamento">Em andamento</button>
    </x-slot>

    <div class="py-12">
        <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Processos Situação:
        </h2>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-justificativa
                id="{{ 1 }}"
                nome="{{ $nome }}"
                matricula="{{ $matricula }}"
                email="{{ $email }}"
                cpf="{{ $cpf }}"
                datas="{{ $datas }}"
                :andamento="$currentStatus ?? 'em_andamento'"
                :anexos="['requerimento_TSI202420892.png', 'hbshdbfhbaajcmsncanjbs.png', 'bshdbfhbaajcmsnjcanbs.img']"
                observacoes="{{ $observacoes }}"
                class="justificativa-item" />
        </div>
    </div>

</x-appcradt>
