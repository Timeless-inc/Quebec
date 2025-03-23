<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="{{ asset('js/filterJustificativas.js') }}"></script>

<x-appcradt>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prioridades:
                </h2>
                <div class="mt-2">
                    <button onclick="filterJustificativas('todos')" type="button" class="btn btn-sm btn-secondary filter-btn" data-status="todos">Todos</button>
                    <button onclick="filterJustificativas('pendente')" type="button" class="btn btn-sm btn-warning filter-btn" data-status="pendente">Atenção</button>
                    <button onclick="filterJustificativas('indeferido')" type="button" class="btn btn-sm btn-danger filter-btn" data-status="indeferido">Indeferido</button>
                    <button onclick="filterJustificativas('finalizado')" type="button" class="btn btn-sm btn-success filter-btn" data-status="finalizado">Resolvido</button>
                    <button onclick="filterJustificativas('em_andamento')" type="button" class="btn btn-sm btn-info filter-btn" data-status="em_andamento">Em andamento</button>
                </div>
            </div>

            <div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
                    + Novo Evento
                </button>
            </div>
        </div>
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

    <!-- Eventos Acadêmicos Section -->
    <div class="py-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Eventos Acadêmicos:
        </h2>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($events->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="row">
                    @foreach($events as $event)
                    <div class="col-md-4 mb-3">
                        <div class="card {{ $event->isExpiringSoon() ? 'border-warning' : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <p class="card-text">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                </p>

                                @if($event->isExpiringSoon())
                                <div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> Evento próximo de encerramento
                                </div>
                                @elseif($event->daysUntilExpiration() > 0 && $event->daysUntilExpiration() <= 3)
                                    <small class="text-warning d-block mt-2">
                                    <i class="fas fa-clock"></i> Expira em {{ $event->daysUntilExpiration() }} dia(s)
                                    </small>
                                    @endif
                            </div>
                        </div>
                    </div>

                    <x-event-edit :event="$event" />
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <p class="mb-0">Não há eventos acadêmicos para exibir no momento.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Processos Section -->
    <div class="py-6">
        <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Processos Situação:
        </h2>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($requerimentos->count() > 0)
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
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <p class="mb-0">Não há requerimentos para exibir no momento.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $requerimentos->links('pagination::bootstrap-5') }}
    </div>

    <x-event-add />
</x-appcradt>