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
'finalizado_por'
])

<div class="justificativa-item relative" id="justificativa-{{ $id }}" data-status="{{ $status }}">

    @if(now()->diffInDays($requerimento->created_at) < 7)
        <span class="absolute top-6 right-6 px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-full">Novo</span>
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
                        <p><span class="font-semibold text-gray-600">E-mail:</span> {{ $email }}</p>
                        <p><span class="font-semibold text-gray-600">CPF:</span> {{ $cpf }}</p>
                        <p><span class="font-semibold text-gray-600">Data:</span> {{ $datas }}</p>
                        <p><span class="font-semibold text-gray-600">Key:</span> {{ $key }}</p>

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

                    <div class="space-y-6 w-full max-w-md">
                        <div class="relative min-h-[120px]">
                            <h5 class="text-xl font-bold text-gray-800">Anexos:</h5>
                            <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                            <ul class="space-y-2 mt-2">
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
                                    <a href="{{ asset('storage/'.$path) }}" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50" target="_blank">
                                        <i class="fas fa-file-download mr-2"></i> {{ basename($path) }}
                                    </a>
                                </li>
                                @endif
                                @endforeach
                                @elseif(!empty($anexoItem))
                                <li>
                                    <a href="{{ asset('storage/'.$anexoItem) }}" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50" target="_blank">
                                        <i class="fas fa-file-download mr-2"></i> {{ basename($anexoItem) }}
                                    </a>
                                </li>
                                @endif
                                @endforeach
                                @else
                                <li class="text-gray-500 italic">Sem anexos</li>
                                @endif
                            </ul>
                        </div>
                        @if($requerimento->status === 'finalizado' && isset($requerimento->anexos_finalizacao) && !empty($requerimento->anexos_finalizacao))
                        <div class="relative min-h-[120px] mt-4">
                            <h5 class="text-xl font-bold text-gray-800">Anexos da Finalização:</h5>
                            <div class="absolute -bottom-1 left-0 w-16 h-1 bg-green-300 rounded"></div>
                            <ul class="space-y-2 mt-2">
                                @php
                                $anexosFinalizacao = is_string($requerimento->anexos_finalizacao) ? json_decode($requerimento->anexos_finalizacao, true) : $requerimento->anexos_finalizacao;
                                $anexosFinalizacao = is_array($anexosFinalizacao) ? array_filter($anexosFinalizacao) : [];
                                @endphp

                                @if(count($anexosFinalizacao) > 0)
                                @foreach($anexosFinalizacao as $anexo)
                                <li>
                                    <a href="{{ asset('storage/'.$anexo) }}" class="inline-flex items-center px-3 py-1 text-sm text-green-600 border border-green-600 rounded-md hover:bg-green-50" target="_blank">
                                        <i class="fas fa-file-download mr-2"></i> {{ basename($anexo) }}
                                    </a>
                                </li>
                                @endforeach
                                @else
                                <li class="text-gray-500 italic">Sem anexos</li>
                                @endif
                            </ul>
                        </div>
                        @endif

                        <div class="relative min-h-[120px]">
                            <h5 class="text-xl font-bold text-gray-800">Observações:</h5>
                            <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                            <p class="text-gray-700 mt-2">{{ $observacoes }}</p>
                        </div>

                        @if($requerimento->status === 'indeferido' && $requerimento->motivo)
                        <div class="relative min-h-[120px]">
                            <h5 class="text-xl font-bold text-gray-800">Motivo do Indeferimento:</h5>
                            <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                            <p class="text-gray-700 mt-2">{{ $requerimento->motivo }}</p>
                        </div>
                        @endif

                        @if($requerimento->status === 'pendente' && $requerimento->motivo)
                        <div class="relative min-h-[120px]">
                            <h5 class="text-xl font-bold text-gray-800">Motivo da Pendência:</h5>
                            <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                            <p class="text-gray-700 mt-2">{{ $requerimento->motivo }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4 border-gray-200">

                <div class="flex justify-end gap-3">
                    @if($requerimento->status !== 'finalizado' && $requerimento->status !== 'indeferido')
                    <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST" class="flex gap-3">
                        @csrf
                        @method('PATCH')

                        <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" title="Gerar PDF">
                            <i class="fas fa-file-pdf text-lg"></i>
                        </a>

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
                    @else
                    <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" title="Gerar PDF">
                        <i class="fas fa-file-pdf text-lg"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <x-approve-modal :requerimento="$requerimento" />

        <x-deny-modal :requerimento="$requerimento" />

        <x-pendent-modal :requerimento="$requerimento" />

</div>