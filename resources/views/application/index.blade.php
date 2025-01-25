<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Preencha o formulário:
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-primary overflow-hidden shadow-sm sm:rounded-lg border border-primary bg-opacity-25">
                        <div class="container mt-5">
                                <div class="card-body">
                                    <form>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="nomeCompleto" class="form-label">Nome Completo</label>
                                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="cpf" class="form-label">CPF</label>
                                                <input type="text" class="form-control" id="cpf" name="cpf">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="celular" class="form-label">Celular</label>
                                                <input type="text" class="form-control" id="celular" name="celular">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="rg" class="form-label">RG</label>
                                                <input type="text" class="form-control" id="rg" name="rg">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="orgaoExpedidor" class="form-label">Órgão Expedidor</label>
                                                <input type="text" class="form-control" id="orgaoExpedidor" name="orgaoExpedidor">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="campus" class="form-label">Campus</label>
                                                <input type="text" class="form-control" id="campus" name="campus">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="matricula" class="form-label">Número de Matrícula</label>
                                                <input type="text" class="form-control" id="matricula" name="matricula">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="situacao" class="form-label">Situação</label>
                                                <select class="form-select" id="situacao" name="situacao">
                                                    <option selected>Escolha</option>
                                                    <option value="ativo">Ativo</option>
                                                    <option value="inativo">Inativo</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="curso" class="form-label">Curso</label>
                                                <input type="text" class="form-control" id="curso" name="curso">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="periodo" class="form-label">Período</label>
                                                <select class="form-select" id="periodo" name="periodo">
                                                    <option selected>Escolha</option>
                                                    <option value="manha">Manhã</option>
                                                    <option value="tarde">Tarde</option>
                                                    <option value="noite">Noite</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="turno" class="form-label">Turno</label>
                                                <select class="form-select" id="turno" name="turno">
                                                    <option selected>Escolha</option>
                                                    <option value="diurno">Diurno</option>
                                                    <option value="noturno">Noturno</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="tipoRequisicao" class="form-label">Tipo de Requisição</label>
                                                <select class="form-select" id="tipoRequisicao" name="tipoRequisicao">
                                                    <option selected>Selecione o tipo de requisição</option>
                                                    <option value="documento">Documento</option>
                                                    <option value="solicitacao">Solicitação</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="anexarArquivos" class="form-label">Anexar Arquivos</label>
                                                <input type="file" class="form-control" id="anexarArquivos" name="anexarArquivos">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="observacoes" class="form-label">Observações</label>
                                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Enviar</button>
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