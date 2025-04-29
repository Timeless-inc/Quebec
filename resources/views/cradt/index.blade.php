<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="{{ asset('js/filterJustificativas.js') }}"></script>
<script src="{{ asset('js/datepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">

<x-appcradt>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prioridades:
                </h2>
                <div class="flex items-center mt-2 space-x-2">
                    <button onclick="filterJustificativas('todos')" type="button" class="px-3 py-1 text-sm text-white bg-gray-600 border-gray-400 rounded-md hover:bg-gray-300 filter-btn" data-status="todos">Todos</button>
                    <button onclick="filterJustificativas('pendente')" type="button" class="px-3 py-1 text-sm text-white bg-yellow-600 border-yellow-400 rounded-md hover:bg-yellow-200 filter-btn" data-status="pendente">Pendente</button>
                    <button onclick="filterJustificativas('finalizado')" type="button" class="px-3 py-1 text-sm text-white bg-green-600 border-green-400 rounded-md hover:bg-green-200 filter-btn" data-status="finalizado">Resolvido</button>
                    <button onclick="filterJustificativas('indeferido')" type="button" class="px-3 py-1 text-sm text-white bg-red-600 border-red-400 rounded-md hover:bg-red-200 filter-btn" data-status="indeferido">Indeferido</button>
                    <button onclick="filterJustificativas('em_aberto')" type="button" class="px-3 py-1 text-sm text-white bg-blue-600 border-blue-400 rounded-md hover:bg-blue-400 filter-btn" data-status="em_aberto">Em Aberto</button>
                </div>
            </div>

            <div class="mt-8">
                <button type="button" class="px-3 py-1 text-sm text-white bg-gray-600 rounded-md hover:bg-gray-800" onclick="openConfigModal()">
                    <i class="fas fa-cog"></i> Configurar Eventos
                </button>
                <button type="button" class="px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-800" data-bs-toggle="modal" data-bs-target="#eventModal">
                    + Novo Evento
                </button>
                <button type="button" class="px-3 py-1 text-sm text-white bg-yellow-700 rounded-md hover:bg-yellow-900" data-bs-toggle="modal" data-bs-target="#eventExceptionModal">
                    ⚠ Evento Exceção
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container mt-4">
        <div class="flex justify-center">
            <div class="w-full md:w-2/3">
                <form action="{{ route('cradt.index') }}" method="GET" class="mb-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex-1">
                            <input type="text"
                                class="w-full border border-gray-400 rounded-md p-2"
                                name="search"
                                placeholder="Buscar por nome, CPF, matrícula..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="relative">
                            <input
                                type="date"
                                class="form-control border border-gray-400 rounded-md p-2"
                                id="datePicker"
                                name="date_filter"
                                value="{{ request('date_filter') ? (strpos(request('date_filter'), '/') !== false ? \Carbon\Carbon::createFromFormat('d/m/Y', request('date_filter'))->format('Y-m-d') : request('date_filter')) : '' }}">
                            <label for="datePicker" class="text-xs text-gray-500 absolute -top-5 left-0">Filtrar por data</label>
                        </div>

                        @if(request('search') || request('date_filter'))
                        <button onclick="clearFilters()" class="bg-gray-400 text-white px-3 py-2 rounded-md hover:bg-gray-500" type="button">
                            <i class="fas fa-times"></i> Limpar filtros
                        </button>
                        @endif

                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                    <input type="hidden" name="status" value="{{ $currentStatus ?? 'todos' }}">
                </form>
            </div>
        </div>
    </div>

    <!-- Eventos Acadêmicos Section -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2">
                Eventos Acadêmicos:
            </h2>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($events->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="row">
                    @foreach($events as $event)
                    <div class="col-md-4 mb-3">
                        <div class="card {{ $event->isEndingToday() ? 'border-danger' : ($event->isExpiringSoon() ? 'border-warning' : '') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                    <div>
                                        @if(!$event->is_exception)
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @else
                                        <span class="badge bg-warning text-dark" title="Eventos de exceção não podem ser editados">
                                            <i class="fas fa-lock"></i> Exceção
                                        </span>
                                        @endif
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

                                @if($event->isEndingToday())
                                <div class="alert alert-danger py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> Evento próximo de encerramento
                                </div>
                                @elseif($event->daysUntilExpiration() == 1)
                                <div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-clock"></i> Este evento vai encerrar em 1 dia
                                </div>
                                @elseif($event->daysUntilExpiration() > 1 && $event->daysUntilExpiration() <= 3)
                                    <div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-clock"></i> Este evento vai encerrar em {{ $event->daysUntilExpiration() }} dias
                                </div>
                                @endif
                                @if($event->is_exception && $event->exceptionUser)
                                <p class="card-text small text-muted">
                                    <strong>Aluno da exceção e CPF:</strong> {{ $event->exceptionUser->name }} {{ $event->exceptionUser->cpf }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!$event->is_exception)
                    <x-event-edit :event="$event" />
                    @endif
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2">
                @if($currentStatus == 'pendente')
                    Processos em Atenção:
                @elseif($currentStatus == 'indeferido')
                    Processos Indeferidos:
                @elseif($currentStatus == 'finalizado')
                    Processos Resolvidos:
                @elseif($currentStatus == 'em_andamento')
                    Processos em Andamento:
                @elseif($currentStatus == 'em_aberto')
                    Processos em Aberto:
                @else
                    Todos os Processos:
                @endif
            </h2>
        </div> 

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="requerimentos-container">
            @if($requerimentos->count() > 0)
            @foreach($requerimentos as $requerimento)
            @php
                $dadosExtra = $requerimento->dadosExtra ? json_decode($requerimento->dadosExtra, true) : [];
            @endphp
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
                tipoRequisicao="{{ $requerimento->tipoRequisicao }}"
                key="{{ $requerimento->key }}"
                finalizado_por="{{ $requerimento->finalizado_por }}"
                :dadosExtra="$dadosExtra"
                class="justificativa-item"
            />
            @endforeach

            <div class="flex justify-center mt-8">
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl">
                    @if($requerimentos->hasPages())
                    {{ $requerimentos->appends(['status' => $currentStatus])->links('pagination::tailwind') }}
                    @else
                    <div class="text-center text-gray-500">
                        <p>Mostrando todos os resultados</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <p class="mb-0">Não há requerimentos para exibir no momento.</p>
            </div>
            @endif
        </div>
    </div>

    <x-event-add />
    <x-event-add-exception />
    <x-event-config />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status') || 'todos';
            
            currentStatus = status;
            
            highlightActiveButton(status);
            
            filterJustificativas(status);
        });
    </script>

    <script src="{{ asset('js/requerimentos-realtime.js') }}"></script>

<style>
    input[type="date"] {
        min-width: 180px;
        cursor: pointer;
        padding-right: 10px;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        padding: 5px;
        position: absolute;
        right: 0;
        opacity: 0.6;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
</style>
</x-appcradt>