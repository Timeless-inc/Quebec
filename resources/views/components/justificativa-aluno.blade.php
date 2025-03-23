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
])

<div class="justificativa-item relative" id="justificativa-{{ $id }}" data-status="{{ $status }}">
    <div class="flex bg-gray-50 border-2 border-gray-200 rounded-lg mb-6">
        <div class="w-12 flex items-center justify-center">
            <div class="bg-teal-400 rounded w-2 h-4/5"></div>
        </div>

        <div class="flex-1 p-6">
            <div class="flex justify-between gap-6">
                <div class="space-y-2">
                    <h5 class="text-xl font-bold text-gray-800 mb-3">{{ $tipoRequisicao }}</h5>
                    <p><span class="font-semibold text-gray-600">Nome:</span> {{ $nome }}</p>
                    <p><span class="font-semibold text-gray-600">Matrícula:</span> {{ $matricula }}</p>
                    <p><span class="font-semibold text-gray-600">E-mail:</span> {{ $email }}</p>
                    <p><span class="font-semibold text-gray-600">CPF:</span> {{ $cpf }}</p>
                    <p><span class="font-semibold text-gray-600">Data:</span> {{ $datas }}</p>

                    <div class="mt-2">
                        <span class="font-semibold text-gray-600">Status:</span>
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
                                <a href="{{ asset('storage/'.$path) }}" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 border-2 border-blue-600 rounded-md hover:bg-blue-50" target="_blank">
                                    <i class="fas fa-file-download mr-2"></i> {{ basename($path) }}
                                </a>
                            </li>
                            @endif
                            @endforeach
                            @elseif(!empty($anexoItem))
                            <li>
                                <a href="{{ asset('storage/'.$anexoItem) }}" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 border border-gray-200 rounded-md hover:bg-blue-50" target="_blank">
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

                    <div class="relative min-h-[120px]">
                        <h5 class="text-xl font-bold text-gray-800">Observações:</h5>
                        <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                        <p class="text-gray-700 mt-2">{{ $observacoes }}</p>
                    </div>

                    @if($status === 'indeferido' && $motivo)
                    <div class="relative min-h-[120px]">
                        <h5 class="text-xl font-bold text-gray-800">Motivo do Indeferimento:</h5>
                        <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                        <p class="text-gray-700 mt-2">{{ $motivo }}</p>
                    </div>
                    @endif

                    @if($status === 'pendente' && $motivo)
                    <div class="relative min-h-[120px]">
                        <h5 class="text-xl font-bold text-gray-800">Motivo da Pendência:</h5>
                        <div class="absolute -bottom-1 left-0 w-16 h-1 bg-blue-300 rounded"></div>
                        <p class="text-gray-700 mt-2">{{ $motivo }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="my-4 border-gray-200">

            <div class="flex justify-end gap-3">
                <a href="{{ route('requerimento.pdf', ['id' => $id]) }}" target="_blank" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 border border-gray-400 rounded-md hover:bg-gray-300" title="Baixar PDF">
                    <i class="fas fa-file-pdf text-lg"></i>
                </a>

                @if($status === 'pendente')
                <a href="{{ route('application.edit', $id) }}" class="w-8 h-8 flex items-center justify-center text-white bg-blue-600 border-2 border-blue-700 rounded-md hover:bg-blue-700" title="Corrigir">
                    <i class="fas fa-edit text-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>