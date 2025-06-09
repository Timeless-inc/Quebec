<title>SRE - Professor</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard do Professor') }}
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
                        <div class="flex bg-gray-50 border-2 border-gray-200 rounded-lg">
                            <div class="w-12 flex items-center justify-center">
                                <div class="rounded w-2 h-4/5 {{ $forwarding->status === 'encaminhado' ? 'bg-purple-500' : 
                                        ($forwarding->status === 'deferido' ? 'bg-green-500' : 
           ($forwarding->status === 'indeferido' ? 'bg-red-500' : 
           ($forwarding->status === 'pendente' ? 'bg-yellow-500' : 
           ($forwarding->status === 'devolver' ? 'bg-purple-500' : 'bg-gray-500')))) }}"></div>
                            </div>

                            <div class="flex-1 p-6">
                                <div class="flex justify-between gap-6">
                                    <div class="space-y-2">
                                        <h5 class="text-xl font-bold text-gray-800 mb-3">{{ $forwarding->requerimento->tipoRequisicao }}</h5>
                                        <h6 class="text-lg font-semibold text-gray-700">Requerimento #{{ $forwarding->requerimento->id }}</h6>
                                        <p><span class="font-semibold text-gray-600">Nome:</span> {{ $forwarding->requerimento->nomeCompleto }}</p>
                                        <p><span class="font-semibold text-gray-600">Matrícula:</span> {{ $forwarding->requerimento->matricula }}</p>
                                        <p><span class="font-semibold text-gray-600">Encaminhado em:</span> {{ $forwarding->created_at->format('d/m/Y H:i') }}</p>

                                        <div class="mt-2">
                                            <span class="font-semibold text-gray-600">Status:</span>
                                            @switch($forwarding->status)
                                            @case('encaminhado')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full">Encaminhado</span>
                                            @break
                                            @case('deferido')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Deferido</span>
                                            @break
                                            @case('indeferido')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Indeferido</span>
                                            @break
                                            @case('pendente')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Pendente</span>
                                            @break
                                            @case('devolvido')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">Devolvido</span>
                                            @break
                                            @default
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">{{ ucfirst($forwarding->status) }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 border-gray-200">

                                <div class="flex justify-end gap-3">
                                    <button type="button" class="h-8 px-3 flex items-center justify-center text-white bg-blue-600 rounded-md hover:bg-blue-700 ver-detalhes-btn" data-bs-toggle="modal" data-bs-target="#detalhesModal-{{ $forwarding->requerimento->id }}" title="Ver Detalhes" data-requerimento-id="{{ $forwarding->requerimento->id }}">
                                        <i class="fas fa-info-circle mr-1 text-lg"></i> Ver Detalhes
                                    </button>

                                    @if ($forwarding->status == 'encaminhado')
                                    <div class="flex gap-3">
                                        <form action="{{ route('professor.process', $forwarding->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="deferido">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-white bg-green-600 rounded-md hover:bg-green-700" title="Deferir">
                                                <i class="fas fa-check text-lg"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('professor.process', $forwarding->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="indeferido">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-white bg-red-600 rounded-md hover:bg-red-700" title="Indeferir">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('professor.process', $forwarding->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="pendente">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-white bg-yellow-600 rounded-md hover:bg-yellow-700" title="Pendência">
                                                <i class="fas fa-exclamation text-lg"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="w-8 h-8 flex items-center justify-center text-white bg-purple-500 rounded-md hover:bg-purple-700" data-bs-toggle="modal" data-bs-target="#returnModal{{ $forwarding->id }}" title="Devolver">
                                            <i class="fas fa-reply text-lg"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Modal para devolução -->
                        <div class="modal fade" id="returnModal{{ $forwarding->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $forwarding->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-gray-100 border-b border-gray-200">
                                        <h5 class="modal-title font-bold text-xl text-gray-800" id="returnModalLabel{{ $forwarding->id }}">
                                            <i class="fas fa-reply mr-2 text-gray-600"></i>Devolver Requerimento #{{ $forwarding->requerimento->id }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('professor.return', $forwarding->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label for="internal_message" class="block text-sm font-medium text-gray-700 mb-1">Mensagem Interna (não visível ao aluno):</label>
                                                <textarea class="form-control w-full p-2 border rounded" id="internal_message" name="internal_message" rows="3" placeholder="Informe o motivo da devolução..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-gray-50 border-t border-gray-200">
                                            <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500" data-bs-dismiss="modal">
                                                <i class="fas fa-times mr-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
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
    <!-- Modal de detalhes do requerimento -->
    <div class="modal fade" id="detalhesModal-{{ $forwarding->requerimento->id }}" tabindex="-1" aria-labelledby="detalhesModalLabel-{{ $forwarding->requerimento->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-gray-100 border-b border-gray-200">
                    <h5 class="modal-title font-bold text-xl text-gray-800" id="detalhesModalLabel-{{ $forwarding->requerimento->id }}">
                        <i class="fas fa-info-circle mr-2 text-gray-600"></i>Detalhes do Requerimento #{{ $forwarding->requerimento->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Coluna da esquerda - Informações principais -->
                            <div class="space-y-6">
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Requerimento</h3>
                                    <ul class="space-y-3">
                                        <li><span class="font-medium text-gray-600">Tipo:</span> {{ $forwarding->requerimento->tipoRequisicao }}</li>
                                        <li><span class="font-medium text-gray-600">Data de criação:</span> {{ $forwarding->requerimento->created_at->format('d/m/Y H:i') }}</li>
                                        <li><span class="font-medium text-gray-600">Status atual:</span>
                                            @switch($forwarding->requerimento->status)
                                            @case('encaminhado')
                                            <span class="inline-block px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full">Encaminhado</span>
                                            @break
                                            @case('deferido')
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
                                        </li>
                                        <li><span class="font-medium text-gray-600">Observações:</span>
                                            @if ($forwarding->requerimento->observacoes)
                                            {{ $forwarding->requerimento->observacoes }}
                                            @else
                                            <span class="text-gray-500 italic">Sem observações</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>

                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Aluno</h3>
                                    <ul class="space-y-3">
                                        <li><span class="font-medium text-gray-600">Nome completo:</span> {{ $forwarding->requerimento->nomeCompleto }}</li>
                                        <li><span class="font-medium text-gray-600">Matrícula:</span> {{ $forwarding->requerimento->matricula }}</li>
                                        <li><span class="font-medium text-gray-600">CPF:</span> {{ $forwarding->requerimento->cpf }}</li>
                                        <li><span class="font-medium text-gray-600">Email:</span> {{ $forwarding->requerimento->email }}</li>
                                        <li><span class="font-medium text-gray-600">Telefone:</span> {{ $forwarding->requerimento->celular }}</li>
                                        <li><span class="font-medium text-gray-600">Curso:</span> {{ $forwarding->requerimento->curso }}</li>
                                        <li><span class="font-medium text-gray-600">Período:</span> {{ $forwarding->requerimento->periodo }}</li>
                                        <li><span class="font-medium text-gray-600">Campus:</span> {{ $forwarding->requerimento->campus }}</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Coluna da direita - Anexos e informações adicionais -->
                            <div class="space-y-6">
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
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Adicionais</h3>
                                    <ul class="space-y-3">
                                        @foreach($dadosExtra as $campo => $valor)
                                            @if(!empty($valor) || $valor === 0 || $valor === '0')
                                            <li><span class="font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $campo)) }}:</span> {{ is_array($valor) ? implode(', ', $valor) : $valor }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($temAnexos)
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Anexos</h3>
                                    <ul class="space-y-2">
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
                                                        <li>
                                                            <a href="{{ asset('storage/'.$path) }}" class="flex items-center px-3 py-2 text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors" target="_blank">
                                                                <i class="fas fa-file-download mr-2"></i>
                                                                <span class="text-sm">{{ basename($path) }}</span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                @elseif(!empty($anexoItem))
                                                <li>
                                                    <a href="{{ asset('storage/'.$anexoItem) }}" class="flex items-center px-3 py-2 text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors" target="_blank">
                                                        <i class="fas fa-file-download mr-2"></i>
                                                        <span class="text-sm">{{ basename($anexoItem) }}</span>
                                                    </a>
                                                </li>
                                                @endif
                                            @endforeach
                                        @else
                                        <li class="text-gray-500 italic py-2 px-3 bg-gray-50 rounded">Sem anexos</li>
                                        @endif
                                    </ul>
                                </div>
                                @endif

                                <!-- Observações - só mostrar se houver -->
                                @if(!empty(trim($forwarding->requerimento->observacoes)))
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Observações</h3>
                                    <p class="text-gray-700">{{ $forwarding->requerimento->observacoes }}</p>
                                </div>
                                @endif

                                <!-- Se não houver dados extra, anexos ou observações, mostrar uma mensagem -->
                                @if(!$temDadosExtra && !$temAnexos && empty(trim($forwarding->requerimento->observacoes)))
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <i class="fas fa-info-circle text-blue-500 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-center">Este requerimento não possui informações adicionais, anexos ou observações.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors flex items-center" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</x-app-layout>