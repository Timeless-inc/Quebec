@props([
    'id',
    'nome',
    'matricula',
    'email',
    'cpf',
    'datas',
    'andamento',
    'anexos',
    'observacoes'
])


<div class="justificativa-item" id="justificativa-{{ $id }}" data-status="{{ $andamento }}">
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
                    <span class="fw-bold">Key:</span> {{ $key }} <br>
                    <span class="fw-bold">Andamento:</span>
                    <span class="badge bg-{{ $andamento == 'Pendência' ? 'warning' : ($andamento == 'Finalizado' ? 'success' : 'info') }}">
                        {{ $andamento }}
                    </span>
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
                <button type="button" class="btn btn-secondary">PDF</button>
                <button onclick="updateStatusAndFilter('{{ $id }}', 'resolvido')" class="btn btn-success">Finalizar</button>
                <button onclick="updateStatusAndFilter('{{ $id }}', 'atencao')" class="btn btn-warning">Pendência</button>
                <button onclick="updateStatusAndFilter('{{ $id }}', 'indeferido')" class="btn btn-danger">Indeferir</button>
                <button onclick="updateStatusAndFilter('{{ $id }}', 'em_andamento')" class="btn btn-info">Em andamento</button>
            </div>

        </div>
    </div>
</div>
