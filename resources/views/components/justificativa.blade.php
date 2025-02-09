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
                    <h6 class="fw-bold mb-1">Requerimento #{{ $requerimento->id }}</h3>
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
                </div>
            </div>

            <hr class="my-2">
            <div class="d-flex justify-content-end gap-2">
                <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <a href="{{ route('requerimento.pdf', ['id' => $requerimento->id]) }}" target="_blank" class="btn btn-secondary mb-2">
                        Baixar PDF
                    </a>

                    <!-- <button type="submit" name="status" value="em_andamento" class="btn btn-primary mb-2">Em Andamento</button> -->
                    <button type="submit" name="status" value="finalizado" class="btn btn-success mb-2">Finalizar</button>
                    <button type="submit" name="status" value="indeferido" class="btn btn-danger mb-2">Indeferir</button>
                    <button type="submit" name="status" value="pendente" class="btn btn-warning mb-2">Pendência</button>
            </div>
            </form>
        </div>
    </div>
</div>