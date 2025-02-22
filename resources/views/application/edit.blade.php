<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Requerimento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-primary overflow-hidden shadow-sm sm:rounded-lg border border-primary bg-opacity-25">
                        <div class="container mt-5">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('application.update', $requerimento->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="nomeCompleto" class="form-label">Nome Completo</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->nomeCompleto }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cpf" class="form-label">CPF</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->cpf }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="celular" class="form-label">Celular</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->celular }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $requerimento->email }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rg" class="form-label">RG</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->rg }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="orgaoExpedidor" class="form-label">Órgão Expedidor</label>
                                            <input type="text" class="form-control" id="orgaoExpedidor" name="orgaoExpedidor" value="{{ $requerimento->orgaoExpedidor }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="campus" class="form-label">Campus</label>
                                            <input type="text" class="form-control" id="campus" name="campus" value="{{ $requerimento->campus }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="matricula" class="form-label">Número de Matrícula</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->matricula }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="situacao" class="form-label">Situação</label>
                                            <select class="form-select" id="situacao" name="situacao">
                                                <option value="1" {{ $requerimento->situacao === 'Matriculado' ? 'selected' : '' }}>Matriculado</option>
                                                <option value="2" {{ $requerimento->situacao === 'Graduado' ? 'selected' : '' }}>Graduado</option>
                                                <option value="3" {{ $requerimento->situacao === 'Desvinculado' ? 'selected' : '' }}>Desvinculado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="curso" class="form-label">Curso</label>
                                            <input type="text" class="form-control" id="curso" name="curso" value="{{ $requerimento->curso }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="periodo" class="form-label">Período</label>
                                            <select class="form-select" id="periodo" name="periodo">
                                                @for($i = 1; $i <= 6; $i++)
                                                    <option value="{{ $i }}" {{ $requerimento->periodo == $i ? 'selected' : '' }}>{{ $i }}º</option>
                                                    @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="turno" class="form-label">Turno</label>
                                            <select class="form-select" id="turno" name="turno">
                                                <option value="manhã" {{ $requerimento->turno === 'manhã' ? 'selected' : '' }}>Manhã</option>
                                                <option value="tarde" {{ $requerimento->turno === 'tarde' ? 'selected' : '' }}>Tarde</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="tipoRequisicao" class="form-label">Tipo de Requisição</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->tipoRequisicao }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="anexarArquivos" class="form-label">Anexar Novos Arquivos</label>
                                            <input type="file" class="form-control" id="anexarArquivos" name="anexarArquivos[]" multiple>
                                            @if($requerimento->anexarArquivos)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Arquivos atuais:
                                                    @foreach(json_decode($requerimento->anexarArquivos) as $arquivo)
                                                    <div>
                                                        <a href="{{ asset('storage/' . $arquivo) }}" target="_blank" class="text-decoration-none">
                                                            {{ basename($arquivo) }}
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ $requerimento->observacoes }}</textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Atualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>