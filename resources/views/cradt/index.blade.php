<title>SRE | Timeless</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/filterJustificativas.js') }}"></script>

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prioridades:
        </h2>

        <div class="btn-group" role="group">
            <button onclick="filterJustificativas('todos')" type="button" class="btn btn-sm btn-secondary filter-btn" data-status="todos">Todos</button>
            <button onclick="filterJustificativas('em_andamento')" type="button" class="btn btn-sm btn-primary filter-btn" data-status="em_andamento">Em andamento</button>
            <button onclick="filterJustificativas('finalizado')" type="button" class="btn btn-sm btn-success filter-btn" data-status="finalizado">Finalizado</button>
            <button onclick="filterJustificativas('indeferido')" type="button" class="btn btn-sm btn-danger filter-btn" data-status="indeferido">Indeferido</button>
            <button onclick="filterJustificativas('pendente')" type="button" class="btn btn-sm btn-warning filter-btn" data-status="pendente">Pendente</button>
        </div>
    </x-slot>

    <div class="py-12">
        <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Processos Situação:
        </h2>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($requerimentos as $requerimento)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Requerimento #{{ $requerimento->id }}</h3>
                                <p><strong>Nome:</strong> {{ $requerimento->nomeCompleto }}</p>
                                <p><strong>Matrícula:</strong> {{ $requerimento->matricula }}</p>
                                <p><strong>Email:</strong> {{ $requerimento->email }}</p>
                                <p><strong>CPF:</strong> {{ $requerimento->cpf }}</p>
                                <p><strong>Data:</strong> {{ $requerimento->created_at->format('d/m/Y') }}</p>
                                <p><strong>Observações:</strong> {{ $requerimento->observacoes }}</p>
                                
                                <div class="status-section mt-3">
                                    <strong>Status Atual:</strong>
                                    @switch($requerimento->status ?? 'em_andamento')
                                        @case('em_andamento')
                                            <span class="badge bg-primary">Em Andamento</span>
                                            @break
                                        @case('finalizado')
                                            <span class="badge bg-success">Finalizado</span>
                                            @break
                                        @case('indeferido')
                                            <span class="badge bg-danger">Indeferido</span>
                                            @break
                                        @case('pendente')
                                            <span class="badge bg-warning">Pendente</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="status" value="em_andamento" class="btn btn-primary mb-2">Em Andamento</button>
                                        <button type="submit" name="status" value="finalizado" class="btn btn-success mb-2">Finalizar</button>
                                        <button type="submit" name="status" value="indeferido" class="btn btn-danger mb-2">Indeferir</button>
                                        <button type="submit" name="status" value="pendente" class="btn btn-warning">Pendência</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-appcradt>
