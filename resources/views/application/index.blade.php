<script src="{{ asset('js/annexButton.js') }}"></script>

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
                              <!-- Display validation errors -->
                              @if ($errors->any())
                                  <div class="alert alert-danger">
                                      <ul>
                                          @foreach ($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                              @endif

                              <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row mb-3">
                                      <div class="col-md-4">
                                          <label for="nomeCompleto" class="form-label">Nome Completo</label>
                                          <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="{{ Auth::user()->name }}" readonly>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="cpf" class="form-label">CPF</label>
                                          <input type="text" class="form-control" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" readonly>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="celular" class="form-label">Celular</label>
                                          <input type="text" class="form-control" id="celular" name="celular" value="{{ Auth::user()->celular }}" required>
                                      </div>
                                  </div>

                                  <div class="row mb-3">
                                      <div class="col-md-4">
                                          <label for="email" class="form-label">Email</label>
                                          <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="rg" class="form-label">RG</label>
                                          <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}" readonly>
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
                                          <input type="text" class="form-control" id="matricula" name="matricula" value="{{ Auth::user()->matricula }}" required>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="situacao" class="form-label">Situação</label>
                                          <select class="form-select" id="situacao" name="situacao">
                                              <option selected>Escolha</option>
                                              <option value="1">Matriculado</option>
                                              <option value="2">Graduado</option>
                                              <option value="3">Desvinculado</option>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="row mb-3">
                                      <div class="col-md-4">
                                      <label for="curso" class="form-label">Curso</label>
                                          <select class="form-select" id="curso" name="curso">
                                              <option selected>Escolha</option>
                                              <option value="1">Administrição</option>
                                              <option value="2">Sistemas para Internet</option>
                                              <option value="3">Logistica</option>
                                              <option value="4">Gestão de Qualidade</option>
                                              <option value="5">Informatica para Internet </option>
                                          </select>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="periodo" class="form-label">Período</label>
                                          <select class="form-select" id="periodo" name="periodo">
                                              <option selected>Escolha</option>
                                              <option value="1">1º</option>
                                              <option value="2">2º</option>
                                              <option value="3">3º</option>
                                              <option value="4">4º</option>
                                              <option value="5">5º</option>
                                              <option value="6">6º</option>
                                              <option value="7">7º</option>
                                              <option value="8">8º</option>
                                          </select>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="turno" class="form-label">Turno</label>
                                          <select class="form-select" id="turno" name="turno">
                                              <option selected>Escolha</option>
                                              <option value="manhã">Manhã</option>
                                              <option value="tarde">Tarde</option>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="row mb-3">
                                      <div class="col-md-6">
                                          <label for="tipoRequisicao" class="form-label">Tipo de Requisição</label>
                                          <select class="form-select" id="tipoRequisicao" name="tipoRequisicao">
                                              <option selected>Selecione o tipo de requisição</option>
                                              <option value="1">Admissão por Transferência e Análise Curricular</option>
                                              <option value="2">Ajuste de Matrícula Semestral</option>
                                              <option value="3">Autorização para cursar disciplinas em outras Instituições de Ensino Superior</option>
                                              <option value="4">Cancelamento de Matrícula</option>
                                              <option value="5">Cancelamento de Disciplina</option>
                                              <option value="6">Certificado de Conclusão</option>
                                              <option value="7">Certidão - Autenticidade</option>
                                              <option value="8">Complementação de Matrícula</option>
                                              <option value="9">Cópia Xerox de Documento</option>
                                              <option value="10">Declaração de Colação de Grau e Tramitação de Diploma</option>
                                              <option value="11">Declaração de Matrícula ou Matrícula Vínculo</option>
                                              <option value="12">Declaração de Monitoria</option>
                                              <option value="13">Declaração para Estágio</option>
                                              <option value="14">Diploma 1ªvia/2ªvia</option>
                                              <option value="15">Dispensa da prática de Educação Física</option>
                                              <option value="16">Declaração Tramitação de Diploma</option>
                                              <option value="17">Ementa de disciplina</option>
                                              <option value="18">Guia de Transferência</option>
                                              <option value="19">Histórico Escolar</option>
                                              <option value="20">Isenção de disciplinas cursadas</option>
                                              <option value="21">Justificativa de falta(s) ou prova 2º chamada</option>
                                              <option value="22">Matriz curricular</option>
                                              <option value="23">Reabertura de Matrícula</option>
                                              <option value="24">Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC</option>
                                              <option value="25">Reintegração para Cursar</option>
                                              <option value="26">Solicitação de Conselho de Classe</option>
                                              <option value="27">Trancamento de Matrícula</option>
                                              <option value="28">Transferência de Turno</option>
                                              <option value="29">Outros</option>
                                          </select>
                                      </div>
                                      <div class="col-md-6">
                                          <label for="anexarArquivos" class="form-label">Anexar Arquivos</label>
                                          <input type="file" class="form-control" id="anexarArquivos" name="anexarArquivos[]" multiple>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoRequisicao = document.getElementById('tipoRequisicao');
        const anexoDiv = document.querySelector('[for="anexarArquivos"]').parentElement;
        
        function toggleAnexoVisibility() {
            const showAnexoFor = [1, 3, 4, 5, 10, 28];
            
            if (showAnexoFor.includes(Number(tipoRequisicao.value))) {
                anexoDiv.style.display = 'block';
            } else {
                anexoDiv.style.display = 'none';
            }
        }

        toggleAnexoVisibility();
        
        tipoRequisicao.addEventListener('change', toggleAnexoVisibility);
    });
</script>

