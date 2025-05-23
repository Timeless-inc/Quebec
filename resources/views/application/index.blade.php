<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/annexButton.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
</head>

<body>
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
                            <div class="container mt-3 mt-md-5">
                                <div class="card-body">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" id="applicationForm" novalidate>
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="nomeCompleto" class="form-label">Nome Completo <span id="nomeCompletoRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="{{ Auth::user()->name }}" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="cpf" class="form-label">CPF <span id="cpfRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="celular" class="form-label">Celular <span id="celularRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="celular" name="celular" value="{{ Auth::user()->celular }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="email" class="form-label">Email <span id="emailRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="rg" class="form-label">RG <span id="rgRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="orgaoExpedidor" class="form-label">Órgão Expedidor <span id="orgaoExpedidorRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="orgaoExpedidor" name="orgaoExpedidor">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="campus" class="form-label">Campus <span id="campusRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="campus" name="campus">
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="matricula" class="form-label">Número de Matrícula <span id="matriculaRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="matricula" name="matricula">
                                                    <option value="">Selecione uma matrícula</option>
                                                    <option value="{{ Auth::user()->matricula }}" selected>{{ Auth::user()->matricula }}</option>
                                                    @if(Auth::user()->second_matricula)
                                                        <option value="{{ Auth::user()->second_matricula }}">{{ Auth::user()->second_matricula }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="situacao" class="form-label">Situação <span id="situacaoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="situacao" name="situacao">
                                                    <option value="">Escolha</option>
                                                    <option value="1">Matriculado</option>
                                                    <option value="2">Graduado</option>
                                                    <option value="3">Desvinculado</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="curso" class="form-label">Curso <span id="cursoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="curso" name="curso">
                                                    <option value="">Escolha</option>
                                                    <option value="1">Administração</option>
                                                    <option value="2">Sistemas para Internet</option>
                                                    <option value="3">Logística</option>
                                                    <option value="4">Gestão de Qualidade</option>
                                                    <option value="5">Informática para Internet</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label for="periodo" class="form-label">Período <span id="periodoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="periodo" name="periodo">
                                                    <option value="">Escolha</option>
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
                                                <label for="turno" class="form-label">Turno <span id="turnoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="turno" name="turno">
                                                    <option value="">Escolha</option>
                                                    <option value="manhã">Manhã</option>
                                                    <option value="tarde">Tarde</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label for="tipoRequisicao" class="form-label">Tipo de Requisição <span class="text-danger">*</span></label>
                                                <select class="form-select" id="tipoRequisicao" name="tipoRequisicao" required>
                                                    <option value="">Selecione o tipo de requisição</option>

                                                    <!-- Tipos disponíveis -->
                                                    @foreach($tiposRequisicao as $id => $tipo)
                                                    <option value="{{ $id }}">{{ $tipo }}</option>
                                                    @endforeach

                                                    <!-- Tipos indisponíveis (em vermelho) -->
                                                    @if(isset($tiposIndisponiveis) && count($tiposIndisponiveis) > 0)
                                                    <optgroup label="Indisponíveis no momento">
                                                        @foreach($tiposIndisponiveis as $id => $tipo)
                                                        <option value="{{ $id }}" class="text-danger" disabled style="color: #dc3545 !important; font-style: italic;">
                                                            {{ $tipo }} (Indisponível)
                                                        </option>
                                                        @endforeach
                                                    </optgroup>
                                                    @endif
                                                </select>
                                                <small class="text-muted d-block mt-1">
                                                    Nota: Alguns tipos de requerimento só estão disponíveis durante períodos específicos.
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label d-block">&nbsp;</label>
                                                <div class="dropdown" id="anexoDropdown" style="display: none;">
                                                    <button class="form-select" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="anexoButton" style="text-align: left;">
                                                        Anexos/informações (clique para abrir)
                                                    </button>
                                                    <div class="dropdown-menu p-3" id="anexoDropdownMenu" style="background-color: #f8f9fa; border-radius: 0.375rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                                        <!-- Campos de anexo serão gerados dinamicamente aqui pelo JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="observacoes" class="form-label">Observações</label>
                                                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-end py-3 py-md-4">
                                                <button type="submit" class="btn btn-success" id="submitBtn">
                                                    <span class="button-text">Enviar</span>
                                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
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

    <div class="dropdown-backdrop" style="display: none;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoRequisicao = document.getElementById('tipoRequisicao');
            const anexoDropdown = document.getElementById('anexoDropdown');
            const anexoDropdownMenu = document.getElementById('anexoDropdownMenu');
            const anexoButton = document.getElementById('anexoButton');
            const dropdownBackdrop = document.querySelector('.dropdown-backdrop');
            const form = document.getElementById('applicationForm');
            const selectElement = document.getElementById('tipoRequisicao');
            const options = selectElement.querySelectorAll('option');
            const isMobile = window.innerWidth <= 768;

            options.forEach(option => {
                if (option.classList.contains('indisponivel')) {
                    option.style.color = '#dc3545';
                    option.style.fontStyle = 'italic';
                }
            });

            // Adicionar tooltip nas opções indisponíveis
            selectElement.addEventListener('mouseover', function(e) {
                if (e.target.classList && e.target.classList.contains('indisponivel')) {
                    document.getElementById('tipoIndisponivelAlert').style.display = 'block';
                }
            });

            selectElement.addEventListener('mouseout', function() {
                document.getElementById('tipoIndisponivelAlert').style.display = 'none';
            });

            selectElement.addEventListener('change', function() {
                document.getElementById('tipoIndisponivelAlert').style.display = 'none';
            });

            if (isMobile) {
                anexoButton.addEventListener('click', function() {
                    dropdownBackdrop.style.display = 'block';
                });
                
                dropdownBackdrop.addEventListener('click', function() {
                    dropdownBackdrop.style.display = 'none';
                    anexoDropdownMenu.classList.remove('show');
                });
            }

            // Tipos de requerimento que precisam de informações adicionais ou anexos
            const tiposComAnexos = [1, 10, 15, 20, 21, 28, 30, 31, 32, 6, 13, 14, 19, 24];

            // Mapeamento de tipos de requisição para campos adicionais ou anexos
            const anexosPorTipo = {
                1: [
                    { label: "Declaração de Transferência", name: "anexarArquivos[declaracao_transferencia]", type: "file" },
                    { label: "Histórico Escolar do Ensino Fundamental (original)", name: "anexarArquivos[historico_fundamental]", type: "file" },
                    { label: "Histórico Escolar do Ensino Médio (original)", name: "anexarArquivos[historico_medio]", type: "file" },
                    { label: "Histórico Escolar do Ensino Superior (original)", name: "anexarArquivos[historico_superior]", type: "file" },
                    { label: "Ementas das disciplinas cursadas com Aprovação", name: "anexarArquivos[ementas]", type: "file" }
                ],
                10: [{
                        label: "Atestado Médico OU Cópia da CTPS (Identificação e Contrato)",
                        name: "anexarArquivos[atestado_ou_ctps]",
                        type: "file"
                    },
                    {
                        label: "Declaração da Empresa com o respectivo horário",
                        name: "anexarArquivos[declaracao_empresa]",
                        type: "file"
                    }
                ],
                15: [{
                    label: "Atestado Médico OU Declaração de Unidade Militar",
                    name: "anexarArquivos[atestado_ou_militar]",
                    type: "file"
                }],
                20: [{
                        label: "Histórico Escolar (Fundamental, Médio ou Superior)",
                        name: "anexarArquivos[historico_ou]",
                        type: "file"
                    },
                    {
                        label: "Ementas das disciplinas cursadas com Aprovação",
                        name: "anexarArquivos[ementas]",
                        type: "file"
                    }
                ],
                21: [{
                        label: "Atestado Médico",
                        name: "anexarArquivos[atestado]",
                        type: "file"
                    },
                    {
                        label: "Declaração da Empresa com o respectivo horário",
                        name: "anexarArquivos[declaracao_empresa]",
                        type: "file"
                    },
                    {
                        label: "Ementas das disciplinas cursadas com Aprovação",
                        name: "anexarArquivos[ementas]",
                        type: "file"
                    }
                ],
                28: [{
                    label: "Atestado Médico OU Declaração de Unidade Militar",
                    name: "anexarArquivos[atestado_ou_militar]",
                    type: "file"
                }],
                30: [{
                        label: "Nome do componente curricular",
                        name: "dadosExtra[componente_curricular]",
                        type: "text"
                    },
                    {
                        label: "Nome do professor",
                        name: "dadosExtra[nome_professor]",
                        type: "text"
                    },
                    {
                        label: "Unidade (1ª, 2ª, 3ª, 4ª ou Exame Final)",
                        name: "dadosExtra[unidade]",
                        type: "select",
                        options: ["1ª unidade", "2ª unidade", "3ª unidade", "4ª unidade", "Exame Final"]
                    },
                    {
                        label: "Ano/Semestre",
                        name: "dadosExtra[ano_semestre]",
                        type: "text"
                    }
                ],
                31: [{
                        label: "Nome do componente curricular",
                        name: "dadosExtra[componente_curricular]",
                        type: "text"
                    },
                    {
                        label: "Nome do professor",
                        name: "dadosExtra[nome_professor]",
                        type: "text"
                    },
                    {
                        label: "Unidade (1ª, 2ª, 3ª, 4ª ou Exame Final)",
                        name: "dadosExtra[unidade]",
                        type: "select",
                        options: ["1ª unidade", "2ª unidade", "3ª unidade", "4ª unidade", "Exame Final"]
                    },
                    {
                        label: "Ano/Semestre",
                        name: "dadosExtra[ano_semestre]",
                        type: "text"
                    }
                ],
                32: [{
                        label: "Nome do componente curricular",
                        name: "dadosExtra[componente_curricular]",
                        type: "text"
                    },
                    {
                        label: "Nome do professor",
                        name: "dadosExtra[nome_professor]",
                        type: "text"
                    },
                    {
                        label: "Unidade (1ª, 2ª, 3ª, 4ª ou Exame Final)",
                        name: "dadosExtra[unidade]",
                        type: "select",
                        options: ["1ª unidade", "2ª unidade", "3ª unidade", "4ª unidade", "Exame Final"]
                    },
                    {
                        label: "Ano/Semestre",
                        name: "dadosExtra[ano_semestre]",
                        type: "text"
                    }
                ],
                6: [{
                        label: "Ano",
                        name: "dadosExtra[ano]",
                        type: "text"
                    },
                    {
                        label: "Semestre",
                        name: "dadosExtra[semestre]",
                        type: "select",
                        options: ["1", "2"]
                    }
                ],
                13: [{
                        label: "Ano",
                        name: "dadosExtra[ano]",
                        type: "text"
                    },
                    {
                        label: "Semestre",
                        name: "dadosExtra[semestre]",
                        type: "select",
                        options: ["1", "2"]
                    }
                ],
                14: [{
                        label: "Via",
                        name: "dadosExtra[via]",
                        type: "select",
                        options: ["1ª via", "2ª via"]
                    },
                    {
                        label: "Ano",
                        name: "dadosExtra[ano]",
                        type: "text"
                    },
                    {
                        label: "Semestre",
                        name: "dadosExtra[semestre]",
                        type: "select",
                        options: ["1", "2"]
                    }
                ],
                19: [{
                        label: "Ano",
                        name: "dadosExtra[ano]",
                        type: "text"
                    },
                    {
                        label: "Semestre",
                        name: "dadosExtra[semestre]",
                        type: "select",
                        options: ["1", "2"]
                    }
                ],
                24: [{
                    label: "Opção de Reintegração",
                    name: "dadosExtra[opcao_reintegracao]",
                    type: "select",
                    options: [
                        "Reintegração",
                        "Estágio",
                        "Entrega do Relatório de Estágio",
                        "TCC"
                    ]
                }]
            };

            function updateAnexoDropdown() {
                const selectedType = Number(tipoRequisicao.value);

                anexoDropdown.style.display = 'none';
                anexoDropdownMenu.innerHTML = '';
                if (isMobile) {
                    dropdownBackdrop.style.display = 'none';
                }

                if (tiposComAnexos.includes(selectedType)) {
                    anexoDropdown.style.display = 'block';

                    const titleDiv = document.createElement('div');
                    titleDiv.className = 'dropdown-header text-muted';
                    const tipoDescricao = tipoRequisicao.options[tipoRequisicao.selectedIndex].text;
                    titleDiv.textContent = tipoDescricao || 'Informações Adicionais';
                    titleDiv.style.fontSize = '1rem';
                    anexoDropdownMenu.appendChild(titleDiv);

                    const containerDiv = document.createElement('div');
                    containerDiv.className = 'mb-3';
                    
                    if (isMobile) {
                        const closeButton = document.createElement('button');
                        closeButton.className = 'btn btn-sm btn-secondary w-100 mb-3';
                        closeButton.textContent = 'Fechar';
                        closeButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            anexoDropdownMenu.classList.remove('show');
                            dropdownBackdrop.style.display = 'none';
                        });
                        containerDiv.appendChild(closeButton);
                    }

                    if (anexosPorTipo[selectedType]) {
                        anexosPorTipo[selectedType].forEach((field, index) => {
                            const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`; 
                            const fieldDiv = document.createElement('div');
                            fieldDiv.className = 'mb-3';

                            if (field.type === 'text') {
                                fieldDiv.innerHTML = `
                                <label for="${uniqueId}" class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label} <span class="required-mark" style="color: #ff0000;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="${uniqueId}" name="${field.name}" required>
                            `;
                            } else if (field.type === 'select') {
                                let optionsHtml = '<option value="">Selecione</option>';
                                field.options.forEach(option => {
                                    optionsHtml += `<option value="${option}">${option}</option>`;
                                });
                                fieldDiv.innerHTML = `
                                <label for="${uniqueId}" class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label} <span class="required-mark" style="color: #ff0000;">*</span></label>
                                <select class="form-select form-select-sm" id="${uniqueId}" name="${field.name}" required>
                                    ${optionsHtml}
                                </select>
                            `;
                            } else if (field.type === 'checkbox') {
                                fieldDiv.innerHTML = `
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="${uniqueId}" name="${field.name}" value="1">
                                    <label class="form-check-label" for="${uniqueId}" style="font-size: 0.9rem; color: #000000;">${field.label}</label>
                                </div>
                            `;
                            } else if (field.type === 'file') {
                                fieldDiv.innerHTML = `
                                <label for="${uniqueId}" class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label} <span class="required-mark" style="color: #ff0000;">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input file-input" id="${uniqueId}" name="${field.name}" accept=".pdf,.jpg,.png" required>
                                    <button type="button" class="custom-file-button" data-input-id="${uniqueId}">Escolher arquivo</button>
                                    <span class="file-name">Nenhum arquivo selecionado</span>
                                </div>
                            `;
                            }
                            containerDiv.appendChild(fieldDiv);
                        });
                    }
                    anexoDropdownMenu.appendChild(containerDiv);
                    initializeFileInputs(); // Re-inicializa os eventos de arquivo
                }
            }

            function initializeFileInputs() {
                const fileInputs = document.querySelectorAll('.file-input');
                fileInputs.forEach(input => {
                    const uniqueId = input.id;
                    const customButton = document.querySelector(`.custom-file-button[data-input-id="${uniqueId}"]`);
                    const fileNameSpan = customButton.nextElementSibling;

                    customButton.addEventListener('click', (e) => {
                        e.stopPropagation(); // Impede o dropdown de fechar
                        input.click();
                    });

                    input.addEventListener('change', (e) => {
                        e.stopPropagation(); // Impede o dropdown de fechar
                        if (input.files && input.files.length > 0) {
                            fileNameSpan.textContent = input.files[0].name;
                        } else {
                            fileNameSpan.textContent = 'Nenhum arquivo selecionado';
                        }
                    });

                    input.addEventListener('click', (e) => {
                        e.stopPropagation(); // Impede o dropdown de fechar
                    });
                });

                // Impede o dropdown de fechar ao clicar dentro dele
                anexoDropdownMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }

            function checkRequiredFields() {
                const requiredFields = [
                    'celular', 'orgaoExpedidor', 'campus', 'matricula', 'situacao',
                    'curso', 'periodo', 'turno', 'tipoRequisicao'
                ];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    const requiredMark = document.getElementById(`${field}Required`);
                    if (input) {
                        if (input.tagName === 'SELECT') {
                            const isEmpty = !input.value || input.value === '';
                            requiredMark.style.display = isEmpty ? 'inline' : 'none';
                        } else if (input.type === 'text' || input.type === 'email') {
                            const isEmpty = !input.value || input.value.trim() === '';
                            requiredMark.style.display = isEmpty ? 'inline' : 'none';
                        }
                    }
                });
            }

            function initializeFieldListeners() {
                const fields = [
                    'celular', 'orgaoExpedidor', 'campus', 'matricula', 'situacao',
                    'curso', 'periodo', 'turno', 'tipoRequisicao'
                ];

                fields.forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        input.addEventListener('change', checkRequiredFields);
                        input.addEventListener('input', checkRequiredFields);
                    }
                });

                tipoRequisicao.addEventListener('change', () => {
                    updateAnexoDropdown();
                    checkRequiredFields();
                });
            }

            form.addEventListener('submit', function(e) {
                let hasEmpty = false;
                const requiredFields = [
                    'celular', 'orgaoExpedidor', 'campus', 'matricula', 'situacao',
                    'curso', 'periodo', 'turno', 'tipoRequisicao'
                ];

                // Tipos de requerimento que exigem anexos obrigatórios
                const tiposComAnexosObrigatorios = [1, 10, 15, 20, 21, 28];

                // Validação dos campos principais
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        if (input.tagName === 'SELECT') {
                            if (!input.value || input.value === '') {
                                hasEmpty = true;
                                input.classList.add('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        } else if (input.type === 'text' || input.type === 'email') {
                            if (!input.value || input.value.trim() === '') {
                                hasEmpty = true;
                                input.classList.add('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        }
                    }
                });

                // Validação dos campos dinâmicos no dropdown
                const selectedType = Number(tipoRequisicao.value);
                if (tiposComAnexos.includes(selectedType)) {
                    anexosPorTipo[selectedType].forEach((field, index) => {
                        const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                        const input = document.getElementById(uniqueId);
                        if (input) {
                            if (field.type === 'text' || field.type === 'select') {
                                if (!input.value || input.value.trim() === '') {
                                    hasEmpty = true;
                                    input.classList.add('is-invalid');
                                } else {
                                    input.classList.remove('is-invalid');
                                }
                            } else if (field.type === 'file' && tiposComAnexosObrigatorios.includes(selectedType)) {
                                if (!input.files || input.files.length === 0) {
                                    hasEmpty = true;
                                    input.classList.add('is-invalid');
                                } else {
                                    input.classList.remove('is-invalid');
                                }
                            }
                        }
                    });

                    // Aplica ou remove o contorno vermelho no botão do dropdown
                    if (hasEmpty) {
                        anexoButton.classList.add('is-invalid');
                    } else {
                        anexoButton.classList.remove('is-invalid');
                    }
                } else {
                    anexoButton.classList.remove('is-invalid');
                }

                if (hasEmpty) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios, incluindo os anexos ou informações adicionais, se aplicável.');
                }
            });

            form.addEventListener('submit', function(event) {
                if (document.querySelectorAll('.is-invalid').length > 0) {
                    return;
                }
                
                const spinner = document.getElementById('global-loading-spinner');
                if (spinner) {
                    spinner.classList.remove('hidden');
                }
                
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.classList.add('opacity-75');
                    submitBtn.classList.add('cursor-not-allowed');
                    
                    const buttonText = submitBtn.querySelector('.button-text');
                    const buttonSpinner = submitBtn.querySelector('.spinner-border');
                    
                    if (buttonText && buttonSpinner) {
                        buttonText.classList.add('d-none');
                        buttonSpinner.classList.remove('d-none');
                    }
                }
            });

            updateAnexoDropdown();
            initializeFileInputs();
            initializeFieldListeners();
            checkRequiredFields();
        });
    </script>
    <style>
         .form-control-sm::-webkit-file-upload-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.3rem 0.6rem;
            border-radius: 0.2rem;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .form-control-sm::-webkit-file-upload-button:hover {
            background-color: #0056b3;
        }

        .form-control-sm::-moz-file-upload-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.3rem 0.6rem;
            border-radius: 0.2rem;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .form-control-sm::-moz-file-upload-button:hover {
            background-color: #0056b3;
        }

        #anexoDropdownMenu {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: visible;
            word-wrap: break-word;
            width: auto;
            max-width: 600px;
            min-width: 280px;
        }

        .is-invalid {
            border-color: #ff0000 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25) !important;
        }

        .required-mark {
            margin-left: 4px;
        }

        option.indisponivel {
            color: #dc3545 !important;
            font-style: italic;
        }

        #tipoIndisponivelAlert {
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            margin-top: 0.5rem;
        }

        #tipoRequisicao option:disabled {
            color: #dc3545 !important;
            background-color: #f8d7da;
        }

        .custom-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        .custom-file-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.3rem 0.6rem;
            border-radius: 0.2rem;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-block;
        }

        .custom-file-button:hover {
            background-color: #0056b3;
        }

        .file-name {
            margin-left: 0.5rem;
            color: #000000;
            word-break: break-all;
            display: inline-block;
            max-width: calc(100% - 120px);
            vertical-align: middle;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .row {
                margin-left: -8px;
                margin-right: -8px;
            }
            
            .col-md-4, .col-md-6, .col-12 {
                padding-left: 8px;
                padding-right: 8px;
            }
            
            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }
            
            .form-control, .form-select {
                font-size: 0.95rem;
                padding: 0.375rem 0.5rem;
            }
            
            #anexoDropdownMenu {
                max-width: 100%;
                min-width: 260px;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                max-height: 80vh;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                z-index: 1050;
            }
            
            .custom-file-button {
                width: 100%;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            
            .file-name {
                display: block;
                max-width: 100%;
                margin-left: 0;
                text-align: center;
                margin-bottom: 0.5rem;
            }
            
            .dropdown-backdrop {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 1040;
            }
            
            .mb-3 {
                margin-bottom: 0.75rem !important;
            }
            
            .py-12 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }
            
            .p-6 {
                padding: 1rem !important;
            }
            
            .dropdown-header {
                text-align: center;
                padding: 0.75rem 0;
                font-weight: 500;
                border-bottom: 1px solid #dee2e6;
                margin-bottom: 0.75rem;
            }
            
            #submitBtn {
                width: 100%;
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .mb-2 {
                margin-bottom: 0.5rem !important;
            }
            
            .form-control, .form-select {
                font-size: 16px; 
            }
            
            #anexoDropdownMenu {
                min-width: 90%;
                max-height: 70vh;
            }
            
            .custom-file-button, .file-name {
                font-size: 0.85rem;
            }
        }
    </style>
</body>

</html>