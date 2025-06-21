<title>SRE - Coordenador</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .form-control-sm::-webkit-file-upload-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 0.2rem;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .form-control-sm::-webkit-file-upload-button:hover {
        background-color: #0056b3;
    }

    .form-control-sm::-moz-file-upload-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 0.2rem;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .form-control-sm::-moz-file-upload-button:hover {
        background-color: #0056b3;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard do Coordenador') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Requerimentos Encaminhados para Você</h3>
                    </div>

                    @if ($forwardings->isEmpty())
                    <div class="bg-gray-50 p-4 rounded-md text-gray-500 text-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i> Não há requerimentos encaminhados para você.
                    </div>
                    @else
                    <div class="space-y-6">
                        @foreach ($forwardings as $forwarding)
                        <!-- Card Principal Modernizado -->
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200/50 mb-4 overflow-hidden group">
                            <!-- Header do Card -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <!-- Indicador de Status -->
                                        <div class="w-3 h-3 rounded-full {{ 
                                            $forwarding->status === 'encaminhado' ? 'bg-purple-500' : 
                                            ($forwarding->status === 'finalizado' ? 'bg-emerald-500' : 
                                            ($forwarding->status === 'indeferido' ? 'bg-red-500' : 
                                            ($forwarding->status === 'pendente' ? 'bg-amber-500' : 
                                            ($forwarding->status === 'devolvido' ? 'bg-pink-500' : 'bg-gray-500')))) }} shadow-sm"></div>
                                        
                                        <div>
                                            <h3 class="text-md font-semibold text-gray-900 truncate max-w-xs">#{{ $forwarding->requerimento->id }} - {{ $forwarding->requerimento->tipoRequisicao }}</h3>
                                        </div>
                                    </div>

                                    <!-- Badge de Status -->
                                    <div class="flex items-center space-x-2">
                                        @switch($forwarding->status)
                                            @case('encaminhado')
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full border border-purple-200">
                                                    <i class="fas fa-share mr-1"></i>Encaminhado
                                                </span>
                                                @break
                                            @case('finalizado')
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                                                    <i class="fas fa-check-circle mr-1"></i>Deferido
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
                                            @case('devolvido')
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-pink-700 bg-pink-100 rounded-full border border-pink-200">
                                                    <i class="fas fa-reply mr-1"></i>Devolvido
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full border border-gray-200">
                                                    {{ ucfirst($forwarding->status) }}
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <!-- Conteúdo Principal -->
                            <div class="p-4">
                                <!-- Informações do Usuário -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <i class="fas fa-user w-5 h-5 text-gray-500 flex-shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-gray-500 font-medium mb-1">Nome</p>
                                            <p class="text-sm text-gray-900 font-medium truncate">{{ $forwarding->requerimento->nomeCompleto }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <i class="fas fa-id-card w-5 h-5 text-gray-500 flex-shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-gray-500 font-medium mb-1">Matrícula</p>
                                            <p class="text-sm text-gray-900 font-medium truncate">{{ $forwarding->requerimento->matricula }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                        <i class="fas fa-share w-5 h-5 text-purple-600 flex-shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-purple-600 font-medium mb-1">Encaminhado em</p>
                                            <p class="text-sm text-gray-900 font-medium">{{ $forwarding->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <i class="fas fa-calendar-alt w-5 h-5 text-blue-600 flex-shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-blue-600 font-medium mb-1">Criado em</p>
                                            <p class="text-sm text-gray-900 font-medium">{{ $forwarding->requerimento->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    <div></div> <!-- Spacer para manter alinhamento à direita -->
                                    <div class="flex items-center space-x-2">
                                        <!-- Botão Ver Detalhes -->
                                        <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors ver-detalhes-btn" data-bs-toggle="modal" data-bs-target="#detalhesModal-{{ $forwarding->requerimento->id }}" title="Ver Detalhes" data-requerimento-id="{{ $forwarding->requerimento->id }}">
                                            <i class="fas fa-info-circle mr-1.5"></i>
                                            Detalhes
                                        </button>

                                        @if ($forwarding->status == 'encaminhado')
                                        <!-- Divisor vertical -->
                                        <div class="w-px h-6 bg-gray-300 mx-2"></div>

                                        <!-- Ações principais -->
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-white bg-emerald-600 border border-emerald-700 rounded-lg hover:bg-emerald-700 transition-colors" data-bs-toggle="modal" data-bs-target="#deferirModal-{{ $forwarding->id }}" title="Deferir">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>

                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-white bg-red-600 border border-red-700 rounded-lg hover:bg-red-700 transition-colors" data-bs-toggle="modal" data-bs-target="#indeferirModal-{{ $forwarding->id }}" title="Indeferir">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>

                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-white bg-pink-600 border border-pink-700 rounded-lg hover:bg-pink-700 transition-colors" data-bs-toggle="modal" data-bs-target="#returnModal{{ $forwarding->id }}" title="Devolver">
                                            <i class="fas fa-reply text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para Deferir Modernizado -->
                        <div class="modal fade" id="deferirModal-{{ $forwarding->id }}" tabindex="-1" aria-labelledby="deferirModalLabel-{{ $forwarding->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content bg-gradient-to-br from-white to-gray-50 border-0 shadow-2xl rounded-2xl overflow-hidden">
                                    <!-- Header Modernizado -->
                                    <div class="modal-header bg-gradient-to-r from-emerald-600 via-emerald-700 to-green-700 text-white border-0 px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                                <i class="fas fa-check-circle text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="modal-title text-xl font-bold mb-0" id="deferirModalLabel-{{ $forwarding->id }}">
                                                    Deferir Requerimento
                                                </h5>
                                                <p class="text-emerald-100 text-sm mb-0">#{{ $forwarding->requerimento->id }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('coordinator.process', $forwarding->id) }}" method="POST" enctype="multipart/form-data" class="approve-form">
                                        @csrf
                                        <input type="hidden" name="action" value="finalizado">
                                        <div class="modal-body p-6">
                                            <div class="mb-6">
                                                <label for="resposta_{{ $forwarding->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Resposta (opcional):</label>
                                                <textarea class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" id="resposta_{{ $forwarding->id }}" name="resposta" rows="4" placeholder="Digite sua resposta aqui..."></textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="anexos_{{ $forwarding->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Anexar arquivos (opcional):</label>
                                                <input type="file" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 form-control-sm transition-colors" id="anexos_{{ $forwarding->id }}" name="anexos[]" multiple>
                                                <p class="text-xs text-gray-500 mt-2">Formatos aceitos: PDF, JPG, PNG (máx. 2MB por arquivo)</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200/50 px-6 py-4">
                                            <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                                                <i class="fas fa-times mr-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-sm hover:shadow-md">
                                                <i class="fas fa-check mr-2"></i>Confirmar Deferimento
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para Indeferir Modernizado -->
                        <div class="modal fade" id="indeferirModal-{{ $forwarding->id }}" tabindex="-1" aria-labelledby="indeferirModalLabel-{{ $forwarding->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content bg-gradient-to-br from-white to-gray-50 border-0 shadow-2xl rounded-2xl overflow-hidden">
                                    <!-- Header Modernizado -->
                                    <div class="modal-header bg-gradient-to-r from-red-600 via-red-700 to-pink-700 text-white border-0 px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                                <i class="fas fa-times-circle text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="modal-title text-xl font-bold mb-0" id="indeferirModalLabel-{{ $forwarding->id }}">
                                                    Indeferir Requerimento
                                                </h5>
                                                <p class="text-red-100 text-sm mb-0">#{{ $forwarding->requerimento->id }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('coordinator.process', $forwarding->id) }}" method="POST" class="deny-form">
                                        @csrf
                                        <input type="hidden" name="action" value="indeferido">
                                        <div class="modal-body p-6">
                                            <div class="mb-4">
                                                <label for="resposta_indf_{{ $forwarding->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Motivo do indeferimento:</label>
                                                <textarea class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" id="resposta_indf_{{ $forwarding->id }}" name="resposta" rows="4" required placeholder="Digite o motivo do indeferimento..."></textarea>
                                                <p class="text-xs text-gray-500 mt-2">É necessário informar o motivo do indeferimento.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200/50 px-6 py-4">
                                            <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                                                <i class="fas fa-times mr-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-sm hover:shadow-md">
                                                <i class="fas fa-times mr-2"></i>Confirmar Indeferimento
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para Devolver Modernizado -->
                        <div class="modal fade" id="returnModal{{ $forwarding->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $forwarding->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content bg-gradient-to-br from-white to-gray-50 border-0 shadow-2xl rounded-2xl overflow-hidden">
                                    <!-- Header Modernizado -->
                                    <div class="modal-header bg-gradient-to-r from-pink-600 via-pink-700 to-rose-700 text-white border-0 px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                                <i class="fas fa-reply text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="modal-title text-xl font-bold mb-0" id="returnModalLabel{{ $forwarding->id }}">
                                                    Devolver Requerimento
                                                </h5>
                                                <p class="text-pink-100 text-sm mb-0">#{{ $forwarding->requerimento->id }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('coordinator.return', $forwarding->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-6">
                                            <div class="mb-4">
                                                <label for="internal_message" class="block text-sm font-semibold text-gray-700 mb-2">Mensagem Interna (não visível ao aluno):</label>
                                                <textarea class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" id="internal_message" name="internal_message" rows="3" placeholder="Informe o motivo da devolução..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200/50 px-6 py-4">
                                            <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                                                <i class="fas fa-times mr-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-pink-700 text-white font-medium rounded-xl hover:from-pink-700 hover:to-pink-800 transition-all duration-300 shadow-sm hover:shadow-md">
                                                <i class="fas fa-reply mr-2"></i>Devolver
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($forwardings as $forwarding)
    <!-- Modal de Detalhes Modernizado -->
    <div class="modal fade" id="detalhesModal-{{ $forwarding->requerimento->id }}" tabindex="-1" aria-labelledby="detalhesModalLabel-{{ $forwarding->requerimento->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content bg-gradient-to-br from-white to-gray-50 border-0 shadow-2xl rounded-2xl overflow-hidden">
                <!-- Header Modernizado -->
                <div class="modal-header bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 text-white border-0 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-file-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-xl font-bold mb-0" id="detalhesModalLabel-{{ $forwarding->requerimento->id }}">
                                Detalhes do Requerimento
                            </h5>
                            <p class="text-blue-100 text-sm mb-0">#{{ $forwarding->requerimento->id }} • {{ $forwarding->requerimento->tipoRequisicao }}</p>
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
                                    <h6 class="text-lg font-bold text-gray-900 mb-1">{{ $forwarding->requerimento->tipoRequisicao }}</h6>
                                    <p class="text-sm text-gray-600">Criado em {{ $forwarding->requerimento->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full shadow-sm 
                                {{ $forwarding->requerimento->status === 'encaminhado' ? 'text-purple-700 bg-purple-100 border border-purple-200' : 
                                ($forwarding->requerimento->status === 'finalizado' ? 'text-emerald-700 bg-emerald-100 border border-emerald-200' : 
                                ($forwarding->requerimento->status === 'indeferido' ? 'text-red-700 bg-red-100 border border-red-200' : 
                                ($forwarding->requerimento->status === 'pendente' ? 'text-amber-700 bg-amber-100 border border-amber-200' : 'text-gray-700 bg-gray-100 border border-gray-200'))) }}">
                                    <i class="fas {{ $forwarding->requerimento->status === 'encaminhado' ? 'fa-share' : 
                                    ($forwarding->requerimento->status === 'finalizado' ? 'fa-check-circle' : 
                                    ($forwarding->requerimento->status === 'indeferido' ? 'fa-times-circle' : 
                                    ($forwarding->requerimento->status === 'pendente' ? 'fa-exclamation-circle' : 'fa-question-circle'))) }} mr-2"></i>
                                    {{ $forwarding->requerimento->status === 'encaminhado' ? 'Encaminhado' : 
                                    ($forwarding->requerimento->status === 'finalizado' ? 'Deferido' : 
                                    ($forwarding->requerimento->status === 'indeferido' ? 'Indeferido' : 
                                    ($forwarding->requerimento->status === 'pendente' ? 'Pendente' : ucfirst($forwarding->requerimento->status)))) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <!-- Informações do Requerimento Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-file-alt text-blue-600"></i>
                                            </div>
                                            Informações do Requerimento
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-4">
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Tipo</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->tipoRequisicao }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Data de criação</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Status atual</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    @switch($forwarding->requerimento->status)
                                                    @case('encaminhado')
                                                    <span class="inline-block px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full">Encaminhado</span>
                                                    @break
                                                    @case('finalizado')
                                                    <span class="inline-block px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Deferido</span>
                                                    @break
                                                    @case('indeferido')
                                                    <span class="inline-block px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Indeferido</span>
                                                    @break
                                                    @case('pendente')
                                                    <span class="inline-block px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Pendente</span>
                                                    @break
                                                    @default
                                                    <span class="inline-block px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">{{ ucfirst($forwarding->requerimento->status) }}</span>
                                                    @endswitch
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
                                            <p class="text-sm text-gray-700 leading-relaxed">
                                                @if ($forwarding->requerimento->observacoes)
                                                {{ $forwarding->requerimento->observacoes }}
                                                @else
                                                <span class="text-gray-500 italic">Sem observações</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Informações do Aluno Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-user-circle text-indigo-600"></i>
                                            </div>
                                            Informações do Aluno
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Nome completo</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->nomeCompleto }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Matrícula</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->matricula }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">CPF</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->cpf }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Email</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900 break-all">{{ $forwarding->requerimento->email }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Telefone</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->celular }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Curso</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->curso }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Período</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->periodo }}</p>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Campus</label>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-3 rounded-xl border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                    <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->campus }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                // Verificar se há dados extras realmente válidos
                                $temDadosExtra = false;
                                if(!empty($forwarding->requerimento->dadosExtra)) {
                                $dadosExtra = json_decode($forwarding->requerimento->dadosExtra, true);
                                $temDadosExtra = is_array($dadosExtra) && count($dadosExtra) > 0;
                                }

                                // Verificar se há anexos realmente válidos com o mesmo tratamento
                                $temAnexos = false;
                                $anexosStr = $forwarding->requerimento->anexarArquivos;
                                $anexosArray = is_string($anexosStr) ? json_decode($anexosStr, true) : $anexosStr;
                                $anexosArray = is_array($anexosArray) ? array_filter($anexosArray) : [];
                                $temAnexos = count($anexosArray) > 0;
                                @endphp

                                @if($temDadosExtra)
                                <!-- Informações Adicionais Modernizado -->
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                                        <h6 class="text-lg font-bold text-gray-900 flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-clipboard-list text-green-600"></i>
                                            </div>
                                            Informações Adicionais
                                        </h6>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-3">
                                            @foreach($dadosExtra as $campo => $valor)
                                            @if(!empty($valor) || $valor === 0 || $valor === '0')
                                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                <span class="font-semibold text-gray-600 text-sm">{{ ucfirst(str_replace('_', ' ', $campo)) }}:</span>
                                                <span class="text-gray-900 text-sm font-medium">{{ is_array($valor) ? implode(', ', $valor) : $valor }}</span>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($temAnexos)
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
                                            $anexosStr = $forwarding->requerimento->anexarArquivos;
                                            $anexosArray = is_string($anexosStr) ? json_decode($anexosStr, true) : $anexosStr;
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
                                @endif

                                @if(!$temDadosExtra && !$temAnexos && empty(trim($forwarding->requerimento->observacoes)))
                                <div class="bg-white rounded-2xl border border-gray-200/50 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-center">Este requerimento não possui informações adicionais, anexos ou observações.</p>
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
    @endforeach
</x-app-layout>