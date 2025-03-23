<title>SRE | Timeless</title>
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
                    <button onclick="filterJustificativas('todos')" type="button" class="btn btn-sm btn-secondary filter-btn" data-status="todos">Todos</button>
                    <button onclick="filterJustificativas('pendente')" type="button" class="btn btn-sm btn-warning filter-btn" data-status="pendente">Atenção</button>
                    <button onclick="filterJustificativas('indeferido')" type="button" class="btn btn-sm btn-danger filter-btn" data-status="indeferido">Indeferido</button>
                    <button onclick="filterJustificativas('finalizado')" type="button" class="btn btn-sm btn-success filter-btn" data-status="finalizado">Resolvido</button>
                    <button onclick="filterJustificativas('em_andamento')" type="button" class="btn btn-sm btn-info filter-btn" data-status="em_andamento">Em andamento</button>
                </div>
            </div>
            <div>
                <a href="{{ route('application') }}">
                    <button type="button" class="btn btn-success opacity-80 border border-teal-950 border-solid border-success rounded">+ Novo requerimento</button>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Events Section -->
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
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <p class="card-text">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                    @if(\Carbon\Carbon::parse($event->end_date)->isPast())
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> Evento próximo de encerramento
                                    </small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
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
                motivo="{{ $requerimento->motivo }}"
                class="justificativa-item" />
            @endforeach
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <p class="mb-3">Você ainda não possui nenhum requerimento. Comece agora mesmo!</p>
                <a href="{{ route('application') }}" class="btn btn-success">Fazer Requerimento</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>