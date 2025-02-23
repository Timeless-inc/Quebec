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
        <button onclick="filterJustificativas('pendente')" type="button" class="btn btn-sm btn-warning filter-btn" data-status="pendente">Atenção</button>
        <button onclick="filterJustificativas('indeferido')" type="button" class="btn btn-sm btn-danger filter-btn" data-status="indeferido">Indeferido</button>
        <button onclick="filterJustificativas('finalizado')" type="button" class="btn btn-sm btn-success filter-btn" data-status="finalizado">Resolvido</button>
        <button onclick="filterJustificativas('em_andamento')" type="button" class="btn btn-sm btn-info filter-btn" data-status="em_andamento">Em andamento</button>
        
        <button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#eventModal">
            + Novo Evento
        </button>
    </x-slot>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('cradt.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text"
                               class="form-control border border-secundary"
                               name="search"
                               placeholder="Buscar por nome, CPF, matrícula..."
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-12">
                <h3 class="text-xl font-semibold mb-3">Eventos Acadêmicos</h3>
                <div class="row">
                    @foreach($events as $event)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">{{ $event->title }}</h5>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('events.update', $event->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Evento</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Título do Evento</label>
                                                <input type="text" class="form-control" name="title" value="{{ $event->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Data de Início</label>
                                                <input type="date" class="form-control" name="start_date" value="{{ $event->start_date }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Data de Término</label>
                                                <input type="date" class="form-control" name="end_date" value="{{ $event->end_date }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Atualizar Evento</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Processos Situação:
        </h2>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($requerimentos as $requerimento)
            <x-justificativa
                id="{{ $requerimento->id }}"
                nome="{{ $requerimento->nomeCompleto }}"
                matricula="{{ $requerimento->matricula }}"
                email="{{ $requerimento->email }}"
                cpf="{{ $requerimento->cpf }}"
                datas="{{ $requerimento->created_at->format('d/m/Y') }}"
                status="{{ $requerimento->status }}"
                :anexos="[$requerimento->anexarArquivos]"
                observacoes="{{ $requerimento->observacoes }}"
                motivo="{{ $requerimento->motivo }}"
                class="justificativa-item" />
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $requerimentos->links('pagination::bootstrap-5') }}
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Criar Novo Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título do Evento</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data de Início</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data de Término</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-appcradt>
