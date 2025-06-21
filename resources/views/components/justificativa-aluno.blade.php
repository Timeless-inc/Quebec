@props([
    'id',
    'nome',
    'matricula',
    'email',
    'cpf',
    'datas',
    'andamento',
    'anexos',
    'observacoes',
    'status',
    'requerimento',
    'resposta',
    'motivo',
    'anexos_finalizacao',
    'tipoRequisicao',
    'dadosExtra' => [],
])

<div class="justificativa-item relative" id="justificativa-{{ $id }}" data-status="{{ $status }}">
    @if(isset($requerimento) && now()->diffInDays($requerimento->created_at) < 7)
        <span class="absolute -top-2 -right-2 px-2 py-1 text-xs font-semibold text-white bg-emerald-500 rounded-lg shadow-lg z-10">Novo</span>
    @endif

    <!-- Card Principal Modernizado -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200/50 mb-4 overflow-hidden group">
        <!-- Header do Card -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Indicador de Status -->
                    <div class="w-3 h-3 rounded-full {{ 
                        $status === 'em_andamento' || $status === 'encaminhado' ? 'bg-blue-500' : 
                        ($status === 'finalizado' ? 'bg-emerald-500' : 
                        ($status === 'indeferido' ? 'bg-red-500' : 
                        ($status === 'pendente' ? 'bg-amber-500' : 'bg-gray-500'))) }} shadow-sm"></div>
                    
                    <div>
                        <h3 class="text-md font-semibold text-gray-900 truncate max-w-xs">{{ $tipoRequisicao }}</h3>
                    </div>
                </div>

                <!-- Badge de Status -->
                <div class="flex items-center space-x-2">
                    @switch($status ?? 'em_andamento')
                        @case('em_andamento')
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full border border-blue-200">
                                <i class="fas fa-clock mr-1"></i>Em Andamento
                            </span>
                            @break
                        @case('encaminhado')
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full border border-blue-200">
                                <i class="fas fa-clock mr-1"></i>Em Andamento
                            </span>
                            @break
                        @case('finalizado')
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                                <i class="fas fa-check-circle mr-1"></i>Finalizado
                            </span>
                            @break
                        @case('indeferido')
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full border border-red-200">
                                <i class="fas fa-times-circle mr-1"></i>Indeferido
                            </span>
                            @break
                        @case('pendente')
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full border border-amber-200">
                                <i class="fas fa-exclamation-circle mr-1"></i>Pendente
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="p-4">
            <!-- Informações do Usuário -->
            @if(isset($requerimento) && in_array($status, ['finalizado', 'indeferido', 'pendente']) && isset($requerimento->finalizado_por))
                <!-- Grid com 4 colunas quando há responsável -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-user w-5 h-5 text-gray-500 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500 font-medium mb-1">Nome</p>
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $nome }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-id-card w-5 h-5 text-gray-500 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500 font-medium mb-1">Matrícula</p>
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $matricula }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 
                    @if($status === 'finalizado') 
                        bg-emerald-50 border-emerald-200 
                    @elseif($status === 'indeferido') 
                        bg-red-50 border-red-200 
                    @elseif($status === 'pendente') 
                        bg-amber-50 border-amber-200 
                    @else 
                        bg-emerald-50 border-emerald-200 
                    @endif 
                    rounded-lg border">
                        <i class="fas fa-user-tie w-5 h-5 
                        @if($status === 'finalizado') 
                            text-emerald-600 
                        @elseif($status === 'indeferido') 
                            text-red-600 
                        @elseif($status === 'pendente') 
                            text-amber-600 
                        @else 
                            text-emerald-600 
                        @endif 
                        flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs 
                            @if($status === 'finalizado') 
                                text-emerald-600 
                            @elseif($status === 'indeferido') 
                                text-red-600 
                            @elseif($status === 'pendente') 
                                text-amber-600 
                            @else 
                                text-emerald-600 
                            @endif 
                            font-medium mb-1">Servidor(a)</p>
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $requerimento->finalizado_por }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <i class="fas fa-calendar-alt w-5 h-5 text-blue-600 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-blue-600 font-medium mb-1">Criado em</p>
                            <p class="text-sm text-gray-900 font-medium">{{ isset($requerimento) ? date('d/m/Y H:i', strtotime($requerimento->created_at)) : $datas }}</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Grid com 3 colunas quando não há responsável -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-user w-5 h-5 text-gray-500 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500 font-medium mb-1">Nome</p>
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $nome }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-id-card w-5 h-5 text-gray-500 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500 font-medium mb-1">Matrícula</p>
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $matricula }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <i class="fas fa-calendar-alt w-5 h-5 text-blue-600 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-blue-600 font-medium mb-1">Data</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $datas }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mensagens de Resposta -->
            @if($status === 'finalizado' && isset($resposta) && !empty($resposta))
            <div class="mb-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <h6 class="text-sm font-semibold text-emerald-800 mb-1">Resposta - Finalizado:</h6>
                        <p class="text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($status === 'indeferido' && isset($resposta) && !empty($resposta))
            <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h6 class="text-sm font-semibold text-red-800 mb-1">Resposta - Indeferido:</h6>
                        <p class="text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($status === 'pendente' && isset($resposta) && !empty($resposta))
            <div class="mb-4 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <h6 class="text-sm font-semibold text-amber-800 mb-1">Resposta - Pendente:</h6>
                        <p class="text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ações -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <div></div> <!-- Spacer para manter alinhamento à direita -->
                <div class="flex items-center space-x-2">
                    <!-- Botão Ver Detalhes -->
                    <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors" data-bs-toggle="modal" data-bs-target="#detalhesModal-{{ $id }}" title="Ver Detalhes">
                        <i class="fas fa-info-circle mr-1.5"></i>
                        Detalhes
                    </button>

                    <!-- Botão Gerar PDF -->
                    <a href="{{ route('requerimento.pdf', ['id' => $id]) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-colors" title="Gerar PDF">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </a>
                    
                    @if($status === 'pendente')
                    <!-- Divisor vertical -->
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    <a href="{{ route('application.edit', $id) }}" class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-600 border border-blue-700 rounded-lg hover:bg-blue-700 transition-colors" title="Corrigir">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para exibir detalhes completos MODERNIZADO -->
    <div class="modal fade" id="detalhesModal-{{ $id }}" tabindex="-1" aria-labelledby="detalhesModalLabel-{{ $id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content bg-gradient-to-br from-white to-gray-50 border-0 shadow-2xl rounded-2xl overflow-hidden">
                <!-- Header Modernizado -->
                <div class="modal-header bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 text-white border-0 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-file-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-xl font-bold mb-0" id="detalhesModalLabel-{{ $id }}">
                                Detalhes do Requerimento
                            </h5>
                            <p class="text-blue-100 text-sm mb-0">#{{ $id }} • {{ $tipoRequisicao }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <!-- Status Header Modernizado -->
                    <div class="bg-gradient-to-r from-slate-50 to-gray-100 px-6 py-4 border-b border-gray-200/50">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-lg font-bold text-gray-900 mb-1">{{ $tipoRequisicao }}</h6>
                                    <p class="text-sm text-gray-600">
                                        @if(isset($requerimento))
                                        Criado em {{ date('d/m/Y H:i', strtotime($requerimento->created_at)) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full shadow-sm 
                                {{ $status === 'em_andamento' || $status === 'encaminhado' ? 'text-blue-700 bg-blue-100 border border-blue-200' : 
                                ($status === 'finalizado' ? 'text-emerald-700 bg-emerald-100 border border-emerald-200' : 
                                ($status === 'indeferido' ? 'text-red-700 bg-red-100 border border-red-200' : 
                                ($status === 'pendente' ? 'text-amber-700 bg-amber-100 border border-amber-200' : 'text-gray-700 bg-gray-100 border border-gray-200'))) }}">
                                    <i class="fas {{ $status === 'em_andamento' || $status === 'encaminhado' ? 'fa-clock' : 
                                    ($status === 'finalizado' ? 'fa-check-circle' : 
                                    ($status === 'indeferido' ? 'fa-times-circle' : 
                                    ($status === 'pendente' ? 'fa-exclamation-circle' : 'fa-question-circle'))) }} mr-2"></i>
                                    {{ $status === 'em_andamento' || $status === 'encaminhado' ? 'Em Andamento' : 
                                    ($status === 'finalizado' ? 'Finalizado' : 
                                    ($status === 'indeferido' ? 'Indeferido' : 
                                    ($status === 'pendente' ? 'Pendente' : 'Desconhecido'))) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <!-- Informações Pessoais Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-user-circle text-blue-600"></i>
                                            </div>
                                            Informações Pessoais
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Nome</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $nome }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Matrícula</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $matricula }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">E-mail</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900 break-all">{{ $email }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">CPF</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $cpf }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Data</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $datas }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observações Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-slate-50 to-gray-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-comment-dots text-slate-600"></i>
                                            </div>
                                            Observações
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-4 rounded-xl border border-gray-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $observacoes ?: 'Nenhuma observação fornecida' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Respostas -->
                                @if($status === 'finalizado' && isset($resposta) && !empty($resposta))
                                <div class="bg-white rounded-2xl border border-emerald-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-emerald-100">
                                        <h6 class="text-lg font-bold text-emerald-900 flex items-center">
                                            <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-check-circle text-emerald-600"></i>
                                            </div>
                                            Resposta - Finalizado
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-emerald-50 to-green-50 p-4 rounded-xl border border-emerald-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $resposta }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($status === 'indeferido' && isset($resposta) && !empty($resposta))
                                <div class="bg-white rounded-2xl border border-red-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-red-100">
                                        <h6 class="text-lg font-bold text-red-900 flex items-center">
                                            <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-times-circle text-red-600"></i>
                                            </div>
                                            Resposta - Indeferido
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-xl border border-red-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $resposta }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($status === 'pendente' && isset($resposta) && !empty($resposta))
                                <div class="bg-white rounded-2xl border border-amber-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 px-6 py-4 border-b border-amber-100">
                                        <h6 class="text-lg font-bold text-amber-900 flex items-center">
                                            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-exclamation-circle text-amber-600"></i>
                                            </div>
                                            Resposta - Pendente
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 p-4 rounded-xl border border-amber-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $resposta }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Motivos condicionais -->
                                @if($status === 'indeferido' && isset($motivo) && !empty($motivo))
                                <div class="bg-white rounded-2xl border border-red-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-red-100">
                                        <h6 class="text-lg font-bold text-red-900 flex items-center">
                                            <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-times-circle text-red-600"></i>
                                            </div>
                                            Motivo do Indeferimento
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-xl border border-red-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $motivo }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($status === 'pendente' && isset($motivo) && !empty($motivo))
                                <div class="bg-white rounded-2xl border border-amber-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 px-6 py-4 border-b border-amber-100">
                                        <h6 class="text-lg font-bold text-amber-900 flex items-center">
                                            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-exclamation-circle text-amber-600"></i>
                                            </div>
                                            Motivo da Pendência
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 p-4 rounded-xl border border-amber-200">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $motivo }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="space-y-6">
                                <!-- Informações Adicionais Modernizado -->
                                @if(!empty($dadosExtra) && is_array($dadosExtra) && !empty(array_filter($dadosExtra)))
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-clipboard-list text-indigo-600"></i>
                                            </div>
                                            Informações Adicionais
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-3">
                                            @if(!empty($dadosExtra['ano']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Ano:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['ano'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['semestre']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Semestre:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['semestre'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['via']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Via:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['via'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['opcao_reintegracao']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Opção de Reintegração:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['opcao_reintegracao'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['componente_curricular']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Componente Curricular:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['componente_curricular'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['nome_professor']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Nome do Professor:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['nome_professor'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['unidade']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Unidade:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['unidade'] }}</span>
                                            </div>
                                            @endif
                                            @if(!empty($dadosExtra['ano_semestre']))
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">Ano/Semestre:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ $dadosExtra['ano_semestre'] }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Anexos Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-paperclip text-purple-600"></i>
                                            </div>
                                            Anexos
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-3">
                                            @php
                                            $anexosArray = is_string($anexos) ? json_decode($anexos, true) : $anexos;
                                            $anexosArray = is_array($anexosArray) ? array_filter($anexosArray) : [];
                                            @endphp

                                            @if(count($anexosArray) > 0)
                                            @foreach($anexosArray as $anexoItem)
                                            @if(is_array($anexoItem))
                                            @foreach($anexoItem as $path)
                                            @if(!empty($path))
                                            <a href="{{ asset('storage/'.$path) }}" class="flex items-center p-4 text-blue-600 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 group" target="_blank">
                                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                                                    <i class="fas fa-file-download text-blue-600"></i>
                                                </div>
                                                <span class="text-sm font-medium group-hover:text-blue-700 flex-1">{{ basename($path) }}</span>
                                                <i class="fas fa-external-link-alt text-xs text-blue-400 group-hover:text-blue-600 transition-colors"></i>
                                            </a>
                                            @endif
                                            @endforeach
                                            @elseif(!empty($anexoItem))
                                            <a href="{{ asset('storage/'.$anexoItem) }}" class="flex items-center p-4 text-blue-600 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 group" target="_blank">
                                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                                                    <i class="fas fa-file-download text-blue-600"></i>
                                                </div>
                                                <span class="text-sm font-medium group-hover:text-blue-700 flex-1">{{ basename($anexoItem) }}</span>
                                                <i class="fas fa-external-link-alt text-xs text-blue-400 group-hover:text-blue-600 transition-colors"></i>
                                            </a>
                                            @endif
                                            @endforeach
                                            @else
                                            <div class="text-center py-8 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border-2 border-dashed border-gray-300">
                                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                                </div>
                                                <p class="text-sm text-gray-500 font-medium">Sem anexos disponíveis</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Anexos da Finalização Modernizado -->
                                @if($status === 'finalizado' && isset($anexos_finalizacao) && !empty($anexos_finalizacao))
                                <div class="bg-white rounded-2xl border border-emerald-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-emerald-100">
                                        <h6 class="text-lg font-bold text-emerald-900 flex items-center">
                                            <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-check-circle text-emerald-600"></i>
                                            </div>
                                            Anexos da Finalização
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-3">
                                            @php
                                            $anexosFinalizacao = is_string($anexos_finalizacao) ? json_decode($anexos_finalizacao, true) : $anexos_finalizacao;
                                            $anexosFinalizacao = is_array($anexosFinalizacao) ? array_filter($anexosFinalizacao) : [];
                                            @endphp

                                            @if(count($anexosFinalizacao) > 0)
                                            @foreach($anexosFinalizacao as $anexo)
                                            @php
                                            $anexoPath = $anexo;
                                            if (is_array($anexo)) {
                                                $anexoPath = reset($anexo);
                                            } elseif (strpos($anexo, '[') === 0) {
                                                $tempArray = json_decode($anexo, true);
                                                $anexoPath = is_array($tempArray) ? reset($tempArray) : $anexo;
                                            }
                                            $anexoPath = str_replace('//', '/', $anexoPath);
                                            @endphp
                                            <a href="{{ asset('storage/'.$anexoPath) }}" class="flex items-center p-4 text-emerald-600 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200/50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-all duration-300 group" target="_blank">
                                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-emerald-200 transition-colors">
                                                    <i class="fas fa-file-download text-emerald-600"></i>
                                                </div>
                                                <span class="text-sm font-medium group-hover:text-emerald-700 flex-1">{{ basename($anexoPath) }}</span>
                                                <i class="fas fa-external-link-alt text-xs text-emerald-400 group-hover:text-emerald-600 transition-colors"></i>
                                            </a>
                                            @endforeach
                                            @else
                                            <div class="text-center py-8 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border-2 border-dashed border-gray-300">
                                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                                </div>
                                                <p class="text-sm text-gray-500 font-medium">Sem anexos disponíveis</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Modernizado -->
                <div class="modal-footer bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200/50 px-6 py-4">
                    <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>