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
'motivo',
'tipoRequisicao',
'key',
'finalizado_por',
'dadosExtra' => [],
])
<script src="{{ asset('js/requerimentoVisualizacao.js') }}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="justificativa-item relative" id="justificativa-{{ $id }}" data-status="{{ $status }}">

    @php
    $diasDiferenca = $requerimento->created_at->diffInDays(now());
    $status = $requerimento->status;
    @endphp

        <!-- Tag Novo - com visualização -->
        @if($diasDiferenca < 2 && !($requerimento->visualizado))
            <span class="absolute top-6 right-6 px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-full novo-badge" id="novo-badge-{{ $requerimento->id }}">Novo</span>
            @endif

        <!-- Tag Antigo -->
        @if($diasDiferenca >= 2 && $diasDiferenca <= 5 && ($status=='em_andamento' || $status=='pendente' ))
            <span class="absolute top-6 right-6 px-2 py-1 text-xs font-medium text-gray-800 bg-yellow-400 rounded-full antigo-badge" id="antigo-badge-{{ $requerimento->id }}">Antigo</span>
            @endif

            <!-- Tag Muito Antigo -->
            @if($diasDiferenca > 5 && ($status=='em_andamento' || $status=='pendente'))
            <span class="absolute top-6 right-6 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded-full muito-antigo-badge" id="muito-antigo-badge-{{ $requerimento->id }}">Muito Antigo</span>
            @endif

            <div class="flex bg-gray-50 border-2 border-gray-200 rounded-lg mb-6">
                <div class="w-12 flex items-center justify-center">
                    <div class="rounded w-2 h-4/5 {{ $requerimento->status === 'em_andamento' ? 'bg-blue-500' : 
            ($requerimento->status === 'finalizado' ? 'bg-green-500' : 
            ($requerimento->status === 'indeferido' ? 'bg-red-500' : 
            ($requerimento->status === 'pendente' ? 'bg-yellow-500' : 'bg-gray-500'))) }}"></div>
                </div>

                <div class="flex-1 p-6">
                    <div class="flex justify-between gap-6">
                        <div class="space-y-2">
                            <h5 class="text-xl font-bold text-gray-800 mb-3">{{ $tipoRequisicao }}</h5>
                            <h6 class="text-lg font-semibold text-gray-700">Requerimento #{{ $requerimento->id }}</h6>
                            <p><span class="font-semibold text-gray-600">Nome:</span> {{ $nome }}</p>
                            <p><span class="font-semibold text-gray-600">Matrícula:</span> {{ $matricula }}</p>

                            <div class="mt-2">
                                <span class="font-semibold text-gray-600">Status:</span>
                                @switch($requerimento->status ?? 'em_andamento')
                                @case('em_andamento')
                                <span class="inline-block px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">Em Andamento</span>
                                @break
                                @case('finalizado')
                                <span class="inline-block px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Finalizado</span>
                                @break
                                @case('indeferido')
                                <span class="inline-block px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Indeferido</span>
                                @break
                                @case('pendente')
                                <span class="inline-block px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Pendente</span>
                                @break
                                @endswitch
                            </div>
                            @if(in_array($requerimento->status, ['finalizado', 'indeferido', 'pendente']) && isset($requerimento->finalizado_por))
                            <p>
                                <span class="font-semibold text-gray-600">Servidor(a):</span>
                                <span class="inline-block px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                                    <i class="fas fa-user mr-1"></i>{{ $requerimento->finalizado_por }}
                                </span>
                            </p>
                            @endif
                            @if($requerimento->status === 'finalizado' && isset($requerimento->resposta) && !empty($requerimento->resposta))
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                                <h6 class="text-sm font-semibold text-green-800 mb-1">Resposta:</h6>
                                <p class="text-sm text-gray-700">{{ $requerimento->resposta }}</p>
                            </div>
                            @endif

                            @if($requerimento->status === 'indeferido' && isset($requerimento->resposta) && !empty($requerimento->resposta))
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                                <h6 class="text-sm font-semibold text-red-800 mb-1">Resposta:</h6>
                                <p class="text-sm text-gray-700">{{ $requerimento->resposta }}</p>
                            </div>
                            @endif

                            @if($requerimento->status === 'pendente' && isset($requerimento->resposta) && !empty($requerimento->resposta))
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <h6 class="text-sm font-semibold text-yellow-800 mb-1">Resposta:</h6>
                                <p class="text-sm text-gray-700">{{ $requerimento->resposta }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">

                    <div class="flex justify-end gap-3">
                        <!-- Botão Ver Detalhes - apenas ícone -->
                        <button type="button" class="h-8 px-3 flex items-center justify-center text-white bg-blue-600 rounded-md hover:bg-blue-700 ver-detalhes-btn" data-bs-toggle="modal" data-bs-target="#detalhesModal-{{ $requerimento->id }}" title="Ver Detalhes" data-requerimento-id="{{ $requerimento->id }}">
                            <i class="fas fa-info-circle mr-1 text-lg"></i> Ver Detalhes
                        </button>

                        <!-- Botão Gerar PDF - apenas ícone -->
                        <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" title="Gerar PDF">
                            <i class="fas fa-file-pdf text-lg"></i>
                        </a>

                        <!-- Separador vertical -->
                        @if($requerimento->status !== 'finalizado' && $requerimento->status !== 'indeferido')
                        <div class="h-8 border-l border-gray-300 mx-1"></div>

                        <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST" class="flex gap-3">
                            @csrf
                            @method('PATCH')

                            <button type="button" class="w-8 h-8 flex items-center justify-center text-white bg-green-600 rounded-md hover:bg-green-700" data-bs-toggle="modal" data-bs-target="#finalizacaoModal-{{ $requerimento->id }}" title="Finalizar">
                                <i class="fas fa-check text-lg"></i>
                            </button>
                            <button type="button" class="w-8 h-8 flex items-center justify-center text-white bg-red-600 rounded-md hover:bg-red-700" data-bs-toggle="modal" data-bs-target="#indeferimentoModal-{{ $requerimento->id }}" title="Indeferir">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                            <button type="button" class="w-8 h-8 flex items-center justify-center text-white bg-yellow-600 rounded-md hover:bg-yellow-700" data-bs-toggle="modal" data-bs-target="#pendenciaModal-{{ $requerimento->id }}" title="Pendência">
                                <i class="fas fa-exclamation text-lg"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal para exibir detalhes completos com visualização melhorada -->
            <div class="modal fade" id="detalhesModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="detalhesModalLabel-{{ $requerimento->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-gray-100 border-b border-gray-200">
                            <h5 class="modal-title font-bold text-xl text-gray-800" id="detalhesModalLabel-{{ $requerimento->id }}">
                                <i class="fas fa-file-alt mr-2 text-blue-600"></i>Detalhes do Requerimento #{{ $requerimento->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-0">
                            <!-- Cabeçalho com informações principais -->
                            <div class="bg-blue-50 p-4 border-b border-blue-100 mb-4">
                                <div class="flex flex-wrap items-center justify-between">
                                    <div>
                                        <h6 class="text-lg font-bold text-blue-800">{{ $tipoRequisicao }}</h6>
                                        <p class="text-sm text-blue-700">Criado em: {{ date('d/m/Y H:i', strtotime($requerimento->created_at)) }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-block px-3 py-2 text-sm font-medium rounded-lg 
                                {{ $requerimento->status === 'em_andamento' ? 'text-blue-700 bg-blue-100' : 
                                ($requerimento->status === 'finalizado' ? 'text-green-700 bg-green-100' : 
                                ($requerimento->status === 'indeferido' ? 'text-red-700 bg-red-100' : 
                                ($requerimento->status === 'pendente' ? 'text-yellow-700 bg-yellow-100' : 'text-gray-700 bg-gray-100'))) }}">
                                            <i class="fas {{ $requerimento->status === 'em_andamento' ? 'fa-clock' : 
                                    ($requerimento->status === 'finalizado' ? 'fa-check-circle' : 
                                    ($requerimento->status === 'indeferido' ? 'fa-times-circle' : 
                                    ($requerimento->status === 'pendente' ? 'fa-exclamation-circle' : 'fa-question-circle'))) }} mr-1"></i>
                                            {{ $requerimento->status === 'em_andamento' ? 'Em Andamento' : 
                                    ($requerimento->status === 'finalizado' ? 'Finalizado' : 
                                    ($requerimento->status === 'indeferido' ? 'Indeferido' : 
                                    ($requerimento->status === 'pendente' ? 'Pendente' : 'Desconhecido'))) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <!-- Informações Pessoais em formato de card -->
                                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                                                <i class="fas fa-user-circle mr-2 text-blue-600"></i>Informações Pessoais
                                            </h6>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">Nome:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded">{{ $nome }}</p>
                                                </div>
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">Matrícula:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded">{{ $matricula }}</p>
                                                </div>
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">E-mail:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded">{{ $email }}</p>
                                                </div>
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">CPF:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded">{{ $cpf }}</p>
                                                </div>
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">Data:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded">{{ $datas }}</p>
                                                </div>
                                                <div>
                                                    <p class="mb-1"><span class="font-semibold text-gray-600">Key:</span></p>
                                                    <p class="bg-gray-50 p-2 rounded text-sm">{{ $key }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Observações -->
                                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                                                <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Observações
                                            </h6>
                                            <div class="mt-3 bg-gray-50 p-3 rounded">
                                                <p class="text-gray-700">{{ $observacoes ?: 'Nenhuma observação fornecida' }}</p>
                                            </div>
                                        </div>

                                        <!-- Motivos condicionais -->
                                        @if($requerimento->status === 'indeferido' && $requerimento->motivo)
                                        <div class="bg-white rounded-lg border border-red-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-red-800 border-b border-red-100 pb-2 flex items-center">
                                                <i class="fas fa-times-circle mr-2 text-red-600"></i>Motivo do Indeferimento
                                            </h6>
                                            <div class="mt-3 bg-red-50 p-3 rounded">
                                                <p class="text-gray-700">{{ $requerimento->motivo }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($requerimento->status === 'pendente' && $requerimento->motivo)
                                        <div class="bg-white rounded-lg border border-yellow-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-yellow-800 border-b border-yellow-100 pb-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2 text-yellow-600"></i>Motivo da Pendência
                                            </h6>
                                            <div class="mt-3 bg-yellow-50 p-3 rounded">
                                                <p class="text-gray-700">{{ $requerimento->motivo }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="space-y-6">
                                        <!-- Informações Adicionais -->
                                        @if(!empty($dadosExtra) && is_array($dadosExtra) && !empty(array_filter($dadosExtra)))
                                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                                                <i class="fas fa-clipboard-list mr-2 text-blue-600"></i>Informações Adicionais
                                            </h6>
                                            <div class="mt-3">
                                                <ul class="space-y-2 divide-y divide-gray-100">
                                                    @if(!empty($dadosExtra['ano']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Ano:</span> {{ $dadosExtra['ano'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['semestre']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Semestre:</span> {{ $dadosExtra['semestre'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['via']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Via:</span> {{ $dadosExtra['via'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['opcao_reintegracao']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Opção de Reintegração:</span> {{ $dadosExtra['opcao_reintegracao'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['componente_curricular']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Componente Curricular:</span> {{ $dadosExtra['componente_curricular'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['nome_professor']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Nome do Professor:</span> {{ $dadosExtra['nome_professor'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['unidade']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Unidade:</span> {{ $dadosExtra['unidade'] }}</li>
                                                    @endif
                                                    @if(!empty($dadosExtra['ano_semestre']))
                                                    <li class="py-2"><span class="font-semibold text-gray-600">Ano/Semestre:</span> {{ $dadosExtra['ano_semestre'] }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Anexos -->
                                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                                                <i class="fas fa-paperclip mr-2 text-blue-600"></i>Anexos
                                            </h6>
                                            <div class="mt-3">
                                                <ul class="space-y-2">
                                                    @php
                                                    $anexosArray = is_string($anexos) ? json_decode($anexos, true) : $anexos;
                                                    $anexosArray = is_array($anexosArray) ? array_filter($anexosArray) : [];
                                                    @endphp

                                                    @if(count($anexosArray) > 0)
                                                    @foreach($anexosArray as $anexoItem)
                                                    @if(is_array($anexoItem))
                                                    @foreach($anexoItem as $path)
                                                    @if(!empty($path))
                                                    <li>
                                                        <a href="{{ asset('storage/'.$path) }}" class="flex items-center px-3 py-2 text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors" target="_blank">
                                                            <i class="fas fa-file-download mr-2"></i>
                                                            <span class="text-sm">{{ basename($path) }}</span>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                    @elseif(!empty($anexoItem))
                                                    <li>
                                                        <a href="{{ asset('storage/'.$anexoItem) }}" class="flex items-center px-3 py-2 text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors" target="_blank">
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
                                        </div>

                                        <!-- Anexos da Finalização -->
                                        @if($requerimento->status === 'finalizado' && isset($requerimento->anexos_finalizacao) && !empty($requerimento->anexos_finalizacao))
                                        <div class="bg-white rounded-lg border border-green-200 shadow-sm p-5">
                                            <h6 class="text-lg font-semibold text-green-800 border-b border-green-100 pb-2 flex items-center">
                                                <i class="fas fa-check-circle mr-2 text-green-600"></i>Anexos da Finalização
                                            </h6>
                                            <div class="mt-3">
                                                <ul class="space-y-2">
                                                    @php
                                                    $anexosFinalizacao = is_string($requerimento->anexos_finalizacao) ? json_decode($requerimento->anexos_finalizacao, true) : $requerimento->anexos_finalizacao;
                                                    $anexosFinalizacao = is_array($anexosFinalizacao) ? array_filter($anexosFinalizacao) : [];
                                                    @endphp

                                                    @if(count($anexosFinalizacao) > 0)
                                                    @foreach($anexosFinalizacao as $anexo)
                                                    <li>
                                                        <a href="{{ asset('storage/'.$anexo) }}" class="flex items-center px-3 py-2 text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors" target="_blank">
                                                            <i class="fas fa-file-download mr-2"></i>
                                                            <span class="text-sm">{{ basename($anexo) }}</span>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                    @else
                                                    <li class="text-gray-500 italic py-2 px-3 bg-gray-50 rounded">Sem anexos</li>
                                                    @endif
                                                </ul>
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

            <x-approve-modal :requerimento="$requerimento" />

            <x-deny-modal :requerimento="$requerimento" />

            <x-pendent-modal :requerimento="$requerimento" />

</div>

