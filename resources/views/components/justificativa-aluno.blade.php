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

<div class="justificativa-item" id="justificativa-{{ $id }}" data-status="{{ $status }}">
    <div class="row bg-primary bg-opacity-25 border border-primary rounded shadow-sm mb-4">
        <div class="col-1 d-flex justify-content-center align-items-center">
            <div class="bg-info rounded" style="width: 8px; height: 80%;"></div>
        </div>

        <div class="col-11 p-4">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3">{{ $tipoRequisicao }}</h5>
                    <span class="fw-bold">Nome:</span> {{ $nome }}<br>
                    <span class="fw-bold">Matrícula:</span> {{ $matricula }}<br>
                    <span class="fw-bold">E-mail:</span> {{ $email }}<br>
                    <span class="fw-bold">CPF:</span> {{ $cpf }}<br>
                    <span class="fw-bold">Data:</span> {{ $datas }} <br>
                    <span class="fw-bold">Status:</span>
                    @switch($status ?? 'em_andamento')
                    @case('em_andamento')
                    <span class="badge bg-primary">Em Andamento</span>
                    @break
                    @case('finalizado')
                    <span class="badge bg-success">Finalizado</span>
                    @break
                    @case('indeferido')
                    <span class="badge bg-danger">Indeferido</span>
                    @break
                    @case('pendente')
                    <span class="badge bg-warning">Pendente</span>
                    @break
                    @endswitch
                </div>

                <div>
                <h5 class="fw-bold mb-3">Anexos:</h5>
                <ul class="list-unstyled">
                        @php
                        $anexosArray = is_string($anexos) ? json_decode($anexos, true) : $anexos;
                        @endphp
                        @if($anexosArray)
                        @foreach($anexosArray as $anexoArray)
                        @if(is_array($anexoArray))
                        @foreach($anexoArray as $key => $path)
                        <li>
                            <a href="{{ asset('storage/' . $path) }}" class="text-primary text-decoration-none" target="_blank">
                                {{ basename($path) }}
                            </a>
                        </li>
                        @endforeach
                        @else
                        <li>
                            <a href="{{ asset('storage/' . $anexoArray) }}" class="text-primary text-decoration-none" target="_blank">
                                {{ basename($anexoArray) }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                        @else
                        <li><em>Sem anexos</em></li>
                        @endif
                    </ul>

                    <h5 class="fw-bold mt-4">Observações:</h5>
                    <p>{{ $observacoes }}</p>

                    @if($status === 'indeferido' && $motivo)
                    <h5 class="fw-bold mt-4">Motivo do Indeferimento:</h5>
                    <p>{{ $motivo }}</p>
                    @endif

                    @if($status === 'pendente' && $motivo)
                    <h5 class="fw-bold mt-4">Motivo da Pendência:</h5>
                    <p>{{ $motivo }}</p>
                    @endif
                </div>
            </div>

            <hr class="my-2">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('requerimento.pdf', ['id' => $id]) }}" target="_blank" class="btn btn-secondary mb-2">
                    Baixar PDF
                </a>

                @if($status === 'pendente')
                <a href="{{ route('application.edit', $id) }}" class="btn btn-primary mb-2">
                    Corrigir
                </a>
                @endif


            </div>

        </div>
    </div>
</div>