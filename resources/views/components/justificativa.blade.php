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
    'key'
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
                    <h6 class="fw-bold mb-1">Requerimento #{{ $requerimento->id }}</h6>
                    <span class="fw-bold">Nome:</span> {{ $nome }}<br>
                    <span class="fw-bold">Matrícula:</span> {{ $matricula }}<br>
                    <span class="fw-bold">E-mail:</span> {{ $email }}<br>
                    <span class="fw-bold">CPF:</span> {{ $cpf }}<br>
                    <span class="fw-bold">Data:</span> {{ $datas }} <br>
                    <span class="fw-bold">Key:</span> {{ $key }} <br>

                    <span class="fw-bold">Status:</span>
                    @switch($requerimento->status ?? 'em_andamento')
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
                        @forelse ($anexos as $anexo)
                            <li><a href="{{ $anexo }}" class="text-primary text-decoration-none">{{ $anexo }}</a></li>
                        @empty
                            <li><em>Sem anexos</em></li>
                        @endforelse
                    </ul>

                    <h5 class="fw-bold mt-4">Observações:</h5>
                    <p>{{ $observacoes }}</p>

                    @if($requerimento->status === 'indeferido' && $requerimento->motivo)
                        <h5 class="fw-bold mt-4">Motivo do Indeferimento:</h5>
                        <p>{{ $requerimento->motivo }}</p>
                    @endif

                    @if($requerimento->status === 'pendente' && $requerimento->motivo)
                        <h5 class="fw-bold mt-4">Motivo da Pendência:</h5>
                        <p>{{ $requerimento->motivo }}</p>
                    @endif
                </div>
            </div>

            <hr class="my-2">
            <div class="d-flex justify-content-end gap-2">
                @if($requerimento->status !== 'finalizado' && $requerimento->status !== 'indeferido')
                    <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="btn btn-secondary mb-2">
                            Gerar PDF
                        </a>

                        <button type="submit" name="status" value="finalizado" class="btn btn-success mb-2">Finalizar</button>
                        <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#indeferimentoModal-{{ $id }}">
                            Indeferir
                        </button>
                        <button type="button" class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#pendenciaModal-{{ $id }}">
                            Pendência
                        </button>
                    </form>
                @else
                    <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="btn btn-secondary mb-2">
                        Gerar PDF
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Indeferimento -->
    <div class="modal fade" id="indeferimentoModal-{{ $id }}" tabindex="-1" aria-labelledby="indeferimentoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indeferimentoModalLabel">Motivo do Indeferimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Explique o motivo do indeferimento:</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="status" value="indeferido" class="btn btn-danger">Confirmar Indeferimento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pendência -->
    <div class="modal fade" id="pendenciaModal-{{ $id }}" tabindex="-1" aria-labelledby="pendenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pendenciaModalLabel">Motivo da Pendência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Explique o motivo da pendência:</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="status" value="pendente" class="btn btn-warning">Confirmar Pendência</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
