<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="{{ asset('js/filterJustificativas.js') }}"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prioridades:
                </h2>
                <div class="mt-2">
                    <button onclick="filterJustificativas('em_aberto')" type="button" class="px-3 py-1 text-sm text-white bg-gray-500 border-gray-400 rounded-md hover:bg-gray-300 filter-btn" data-status="todos">Em Aberto</button>
                    <button onclick="filterJustificativas('pendente')" type="button" class="px-3 py-1 text-sm text-white bg-yellow-500 border-yellow-400 rounded-md hover:bg-yellow-200 filter-btn" data-status="pendente">Atenção</button>
                    <button onclick="filterJustificativas('indeferido')" type="button" class="px-3 py-1 text-sm text-white bg-red-500 border-red-400 rounded-md hover:bg-red-200 filter-btn" data-status="indeferido">Indeferido</button>
                    <button onclick="filterJustificativas('finalizado')" type="button" class="px-3 py-1 text-sm text-white bg-green-500 border-green-400 rounded-md hover:bg-green-200 filter-btn" data-status="finalizado">Resolvido</button>
                    <button onclick="filterJustificativas('em_andamento')" type="button" class="px-3 py-1 text-sm text-white bg-blue-500 border-blue-400 rounded-md hover:bg-blue-200 filter-btn" data-status="em_andamento">Em andamento</button>
                </div>
            </div>
            <div class="mt-8">
                <a href="{{ route('application') }}">
                    <button type="button" class="px-3 py-1 text-sm text-white bg-green-600 rounded-md hover:bg-green-800">+ Novo requerimento</button>
                </a>
            </div>
        </div>
    </x-slot>

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
                        <div class="card {{ $event->isEndingToday() ? 'border-danger' : ($event->isExpiringSoon() ? 'border-warning' : '') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $event->title }}</h5>
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

    <!-- Existing Requerimentos Section -->
    <div class="pb-6 pt-2">
        <h2 id="situacao-titulo" class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2 px-64">
            Processos Situação:
        </h2>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($requerimentos->count() > 0)
            @foreach($requerimentos as $requerimento)
            <x-justificativa-aluno
                id="{{ $requerimento->id }}"
                nome="{{ $requerimento->nomeCompleto }}"
                matricula="{{ $requerimento->matricula }}"
                email="{{ $requerimento->email }}"
                cpf="{{ $requerimento->cpf }}"
                datas="{{ $requerimento->created_at->format('d/m/Y') }}"
                status="{{ $requerimento->status }}"
                :anexos="[$requerimento->anexarArquivos]"
                observacoes="{{ $requerimento->observacoes }}"
                resposta="{{ $requerimento->resposta }}"
                :anexos_finalizacao="[$requerimento->anexos_finalizacao]"
                class="justificativa-item" />
            @endforeach
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <p class="mb-3">Você ainda não possui nenhum requerimento. Comece agora mesmo!</p>
                <a href="{{ route('application') }}" class="btn btn-success">Fazer Requerimento</a>
            </div>
            @endif
        </div>

        @if($requerimentos->hasPages())
        <div class="flex justify-center mt-8">
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl">
                {{ $requerimentos->links('pagination::tailwind') }}
            </div>
        </div>
        @endif

    </div>
</x-app-layout>