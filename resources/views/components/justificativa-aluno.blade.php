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
])

<div class="justificativa-item relative" id="justificativa-{{ $id }}" data-status="{{ $status }}">
    <div class="flex bg-gray-50 border-2 border-gray-200 rounded-lg mb-6">
        <div class="w-4 sm:w-12 flex items-center justify-center">
            <div class="rounded w-1 sm:w-2 h-4/5 {{ $status === 'em_andamento' ? 'bg-blue-500' : 
            ($status === 'finalizado' ? 'bg-green-500' : 
            ($status === 'indeferido' ? 'bg-red-500' : 
            ($status === 'pendente' ? 'bg-yellow-500' : 'bg-gray-500'))) }}"></div>
        </div>

        <div class="flex-1 p-3 sm:p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:gap-6">
                <div class="space-y-2 w-full md:w-auto mb-6 md:mb-0">
                    <h5 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $tipoRequisicao }}</h5>
                    <p class="text-sm sm:text-base"><span class="font-semibold text-gray-600">Nome:</span> {{ $nome }}</p>
                    <p class="text-sm sm:text-base"><span class="font-semibold text-gray-600">Matrícula:</span> {{ $matricula }}</p>
                    <p class="text-sm sm:text-base"><span class="font-semibold text-gray-600">E-mail:</span> {{ $email }}</p>
                    <p class="text-sm sm:text-base"><span class="font-semibold text-gray-600">CPF:</span> {{ $cpf }}</p>
                    <p class="text-sm sm:text-base"><span class="font-semibold text-gray-600">Data:</span> {{ $datas }}</p>

                    <div class="mt-2">
                        <span class="font-semibold text-gray-600 text-sm sm:text-base">Status:</span>
                        @switch($status ?? 'em_andamento')
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

                    @if($status === 'finalizado' && isset($resposta) && !empty($resposta))
                    <div class="mt-3 p-2 sm:p-3 bg-green-50 border border-green-200 rounded-md">
                        <h6 class="text-sm font-semibold text-green-800 mb-1">Resposta:</h6>
                        <p class="text-xs sm:text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                    @endif

                    @if($status === 'indeferido' && isset($resposta) && !empty($resposta))
                    <div class="mt-3 p-2 sm:p-3 bg-red-50 border border-red-200 rounded-md">
                        <h6 class="text-sm font-semibold text-red-800 mb-1">Resposta:</h6>
                        <p class="text-xs sm:text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                    @endif

                    @if($status === 'pendente' && isset($resposta) && !empty($resposta))
                    <div class="mt-3 p-2 sm:p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <h6 class="text-sm font-semibold text-yellow-800 mb-1">Resposta:</h6>
                        <p class="text-xs sm:text-sm text-gray-700">{{ $resposta }}</p>
                    </div>
                    @endif
                </div>

                <div class="space-y-4 sm:space-y-6 w-full md:max-w-md">
                    <div class="relative min-h-[100px] sm:min-h-[120px]">
                        <h5 class="text-lg sm:text-xl font-bold text-gray-800">Anexos:</h5>
                        <div class="absolute -bottom-1 left-0 w-12 sm:w-16 h-1 bg-blue-300 rounded"></div>
                        <ul class="space-y-2 mt-2 overflow-x-auto max-w-full">
                            @php
                            $anexosArray = is_string($anexos) ? json_decode($anexos, true) : $anexos;
                            $anexosArray = is_array($anexosArray) ? array_filter($anexosArray) : [];
                            @endphp

                            @if(count($anexosArray) > 0)
                            @foreach($anexosArray as $anexoItem)
                            @if(is_array($anexoItem))
                            @foreach($anexoItem as $path)
                            @if(!empty($path))
                            <li class="mb-2">
                                <a href="{{ asset('storage/'.$path) }}" class="inline-flex items-center px-2 sm:px-3 py-1 text-xs sm:text-sm text-blue-600 border-2 border-blue-600 rounded-md hover:bg-blue-50 break-all" target="_blank">
                                    <i class="fas fa-file-download mr-1 sm:mr-2"></i> <span class="truncate max-w-[150px] sm:max-w-[200px]">{{ basename($path) }}</span>
                                </a>
                            </li>
                            @endif
                            @endforeach
                            @elseif(!empty($anexoItem))
                            <li class="mb-2">
                                <a href="{{ asset('storage/'.$anexoItem) }}" class="inline-flex items-center px-2 sm:px-3 py-1 text-xs sm:text-sm text-blue-600 border border-gray-200 rounded-md hover:bg-blue-50 break-all" target="_blank">
                                    <i class="fas fa-file-download mr-1 sm:mr-2"></i> <span class="truncate max-w-[150px] sm:max-w-[200px]">{{ basename($anexoItem) }}</span>
                                </a>
                            </li>
                            @endif
                            @endforeach
                            @else
                            <li class="text-gray-500 italic text-xs sm:text-sm">Sem anexos</li>
                            @endif
                        </ul>
                    </div>

                    @if($status === 'finalizado')
                    <div class="relative min-h-[100px] sm:min-h-[120px] mt-4">
                        <h5 class="text-lg sm:text-xl font-bold text-gray-800">Anexos da Finalização:</h5>
                        <div class="absolute -bottom-1 left-0 w-12 sm:w-16 h-1 bg-green-300 rounded"></div>
                        <ul class="space-y-2 mt-2 overflow-x-auto max-w-full">
                            @php
                            $anexosFinalizacao = is_string($anexos_finalizacao) ? json_decode($anexos_finalizacao, true) : $anexos_finalizacao;
                            $anexosFinalizacao = is_array($anexosFinalizacao) ? array_filter($anexosFinalizacao) : [];
                            @endphp

                            @if(count($anexosFinalizacao) > 0)
                            @foreach($anexosFinalizacao as $anexo)
                            <li class="mb-2">
                                @php
                                // Extrair o caminho correto do anexo, independente do formato
                                $anexoPath = $anexo;
                                if (is_array($anexo)) {
                                    $anexoPath = reset($anexo); // Pega o primeiro item do array
                                } elseif (strpos($anexo, '[') === 0) {
                                    // Se for uma string que representa um array JSON
                                    $tempArray = json_decode($anexo, true);
                                    $anexoPath = is_array($tempArray) ? reset($tempArray) : $anexo;
                                }
                                // Remover barras duplas se existirem
                                $anexoPath = str_replace('//', '/', $anexoPath);
                                @endphp
                                <a href="{{ asset('storage/'.$anexoPath) }}" class="inline-flex items-center px-2 sm:px-3 py-1 text-xs sm:text-sm text-green-600 border border-green-600 rounded-md hover:bg-green-50 break-all" target="_blank">
                                    <i class="fas fa-file-download mr-1 sm:mr-2"></i> <span class="truncate max-w-[150px] sm:max-w-[200px]">{{ basename($anexoPath) }}</span>
                                </a>
                            </li>
                            @endforeach
                            @else
                            <li class="text-gray-500 italic text-xs sm:text-sm">Sem anexos de finalização</li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <div class="relative min-h-[100px] sm:min-h-[120px]">
                        <h5 class="text-lg sm:text-xl font-bold text-gray-800">Observações:</h5>
                        <div class="absolute -bottom-1 left-0 w-12 sm:w-16 h-1 bg-blue-300 rounded"></div>
                        <p class="text-xs sm:text-sm text-gray-700 mt-2">{{ $observacoes }}</p>
                    </div>
                </div>
            </div>

            <hr class="my-3 sm:my-4 border-gray-200">

            <div class="flex justify-end gap-2 sm:gap-3">
                <a href="{{ route('requerimento.pdf', ['id' => $id]) }}" target="_blank" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-gray-700 bg-gray-200 border border-gray-400 rounded-md hover:bg-gray-300" title="Baixar PDF">
                    <i class="fas fa-file-pdf text-sm sm:text-lg"></i>
                </a>

                @if($status === 'pendente')
                <a href="{{ route('application.edit', $id) }}" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-white bg-blue-600 border-2 border-blue-700 rounded-md hover:bg-blue-700" title="Corrigir">
                    <i class="fas fa-edit text-sm sm:text-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>