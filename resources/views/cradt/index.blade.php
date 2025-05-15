<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="{{ asset('js/filterJustificativas.js') }}"></script>
<script src="{{ asset('js/datepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
<link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
<script src="{{ asset('js/form-loading.js') }}" defer></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

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

    <div class="container mt-4 py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

    <!-- Eventos Acadêmicos Section -->
    @if($events->isNotEmpty())
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-2">
                Eventos Acadêmicos:
            </h2>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
        </div>
    </div>
    @endif

    <!-- Processos Section -->
    <div class="py-6">
        @if(isset($profileChangeGroups))
            <div class="hidden">
                <p>Grupos de solicitações: {{ $profileChangeGroups->count() }}</p>
                @foreach($profileChangeGroups as $groupId => $requests)
                    <p>Grupo {{ $groupId }}: {{ $requests->count() }} solicitações</p>
                @endforeach
            </div>
        @endif

        @if(isset($profileChangeGroups) && $profileChangeGroups->count() > 0)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <h3 class="text-lg font-semibold mb-4">Solicitações de Alteração de Perfil Pendentes</h3>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aluno</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitação</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alterações</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($profileChangeGroups as $groupId => $requests)
                            @php 
                                $firstRequest = $requests->first();
                                $status = $firstRequest->status;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $firstRequest->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Solicitação #{{ substr($groupId, 0, 8) }} <br>
                                    <span class="text-xs text-gray-500">{{ $firstRequest->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        @foreach($requests as $request)
                                            <div class="flex flex-col mb-2 pb-2 border-b border-gray-100">
                                                <span class="font-medium">
                                                    @if($request->field == 'name') Nome
                                                    @elseif($request->field == 'matricula') Matrícula
                                                    @elseif($request->field == 'cpf') CPF
                                                    @elseif($request->field == 'rg') RG
                                                    @endif
                                                </span>
                                                <div class="flex text-sm">
                                                    <span class="text-gray-500 mr-1">Atual:</span> {{ $request->current_value }}
                                                </div>
                                                <div class="flex text-sm">
                                                    <span class="text-gray-500 mr-1">Novo:</span> {{ $request->new_value }}
                                                </div>
                                                <a href="{{ Storage::temporaryUrl($request->document_path, now()->addMinutes(30)) }}" target="_blank" class="text-blue-600 hover:underline text-xs mt-1">Ver documento</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                    @elseif($status == 'needs_review')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Em Revisão</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button type="button" onclick="openApprovalModal('{{ $groupId }}')" class="text-green-600 hover:text-green-900">Deferir</button>
                                        <button type="button" onclick="openRejectionModal('{{ $groupId }}')" class="text-red-600 hover:text-red-900">Indeferir</button>
                                    </div>
                                    
                                    <!-- Approval Modal -->
                                    <div id="approval-modal-{{ $groupId }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-auto text-left">
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">Deferir Solicitação</h3>
                                            <form method="POST" action="{{ route('profile-requests.approve-group', $groupId) }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="approval-comment-{{ $groupId }}" class="block text-sm font-medium text-gray-700 mb-1">Comentário (opcional)</label>
                                                    <textarea id="approval-comment-{{ $groupId }}" name="comment" rows="3" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                                                </div>
                                                <div class="flex justify-end space-x-3">
                                                    <button type="button" onclick="closeApprovalModal('{{ $groupId }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancelar</button>
                                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Rejection Modal -->
                                    <div id="rejection-modal-{{ $groupId }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-auto text-left">
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">Indeferir Solicitação</h3>
                                            <form method="POST" action="{{ route('profile-requests.reject-group', $groupId) }}" id="reject-form-{{ $groupId }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="rejection-comment-{{ $groupId }}" class="block text-sm font-medium text-gray-700 mb-1">Motivo do indeferimento <span class="text-red-600">*</span></label>
                                                    <textarea id="rejection-comment-{{ $groupId }}" name="comment" rows="3" class="w-full border rounded-md px-3 py-2 text-sm" required></textarea>
                                                </div>
                                                <div class="flex justify-end space-x-3">
                                                    <button type="button" onclick="closeRejectionModal('{{ $groupId }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancelar</button>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

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
                class="justificativa-item" />
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

        document.querySelectorAll('.comments-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const requestId = button.getAttribute('data-request-id');
                const commentsForm = document.getElementById(`comments-form-${requestId}`);
                commentsForm.classList.toggle('hidden');
            });
        });

        function openApprovalModal(requestId) {
            document.getElementById(`approval-modal-${requestId}`).classList.remove('hidden');
        }

        function closeApprovalModal(requestId) {
            document.getElementById(`approval-modal-${requestId}`).classList.add('hidden');
        }

        function openRejectionModal(requestId) {
            document.getElementById(`rejection-modal-${requestId}`).classList.remove('hidden');
        }

        function closeRejectionModal(requestId) {
            document.getElementById(`rejection-modal-${requestId}`).classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            const approvalModals = document.querySelectorAll('[id^="approval-modal-"]');
            const rejectionModals = document.querySelectorAll('[id^="rejection-modal-"]');

            approvalModals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            rejectionModals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Form validation for rejection
        document.addEventListener('DOMContentLoaded', function() {
            const rejectionForms = document.querySelectorAll('form[id^="reject-form-"]');

            rejectionForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const commentField = form.querySelector('textarea[name="comment"]');
                    if (!commentField.value.trim()) {
                        event.preventDefault();
                        alert('Por favor, informe o motivo do indeferimento.');
                    }
                });
            });
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