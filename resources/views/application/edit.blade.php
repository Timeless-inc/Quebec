<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">


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

                                <form method="POST" action="{{ route('application.update', $requerimento->id) }}" enctype="multipart/form-data" id="applicationEditForm" novalidate>
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="nomeCompleto" class="form-label">Nome Completo</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->nomeCompleto }}" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="cpf" class="form-label">CPF</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->cpf }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="celular" class="form-label">Celular</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->celular }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $requerimento->email }}" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="rg" class="form-label">RG</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->rg }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="orgaoExpedidor" class="form-label">Órgão Expedidor <span id="orgaoExpedidorRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <input type="text" class="form-control" id="orgaoExpedidor" name="orgaoExpedidor" value="{{ $requerimento->orgaoExpedidor }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="campus" class="form-label">Campus <span id="campusRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <input type="text" class="form-control" id="campus" name="campus" value="{{ $requerimento->campus }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="matricula" class="form-label">Número de Matrícula</label>
                                            <select class="form-select" id="matricula_view" disabled>
                                                <option value="{{ $requerimento->matricula }}" selected>{{ $requerimento->matricula }}</option>
                                                @if(Auth::user()->second_matricula)
                                                    <option value="{{ Auth::user()->second_matricula }}" {{ $requerimento->matricula === Auth::user()->second_matricula ? 'selected' : '' }}>{{ Auth::user()->second_matricula }}</option>
                                                @endif
                                            </select>
                                            <input type="hidden" name="matricula" value="{{ $requerimento->matricula }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="situacao" class="form-label">Situação <span id="situacaoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <select class="form-select" id="situacao" name="situacao" required>
                                                <option value="1" {{ $requerimento->situacao === 'Matriculado' ? 'selected' : '' }}>Matriculado</option>
                                                <option value="2" {{ $requerimento->situacao === 'Graduado' ? 'selected' : '' }}>Graduado</option>
                                                <option value="3" {{ $requerimento->situacao === 'Desvinculado' ? 'selected' : '' }}>Desvinculado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="curso" class="form-label">Curso <span id="cursoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <select class="form-select" id="curso" name="curso" required>
                                                @foreach($cursos as $id => $nome)
                                                <option value="{{ $id }}" {{ $requerimento->curso === $nome ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <label for="periodo" class="form-label">Período <span id="periodoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <select class="form-select" id="periodo" name="periodo" required>
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ $requerimento->periodo == $i ? 'selected' : '' }}>{{ $i }}º</option>
                                                    @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="turno" class="form-label">Turno <span id="turnoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                            <select class="form-select" id="turno" name="turno" required>
                                                <option value="manhã" {{ $requerimento->turno === 'manhã' ? 'selected' : '' }}>Manhã</option>
                                                <option value="tarde" {{ $requerimento->turno === 'tarde' ? 'selected' : '' }}>Tarde</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="tipoRequisicao" class="form-label">Tipo de Requisição</label>
                                            <input type="text" class="form-control" value="{{ $requerimento->tipoRequisicao }}" readonly>
                                            <input type="hidden" id="tipoRequisicao" name="tipoRequisicao" value="{{ array_search($requerimento->tipoRequisicao, $tiposRequisicao) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dropdown" id="anexoDropdown" style="margin-top: 2rem;">
                                                <button class="form-select" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="anexoButton" style="text-align: left;">
                                                    Anexos/informações (clique para abrir)
                                                </button>
                                                <div class="dropdown-menu p-3" id="anexoDropdownMenu" style="background-color: #f8f9fa; border-radius: 0.375rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: auto; max-width: 600px; min-width: 280px; overflow-x: auto;">
                                                    <!-- Campos de anexo serão gerados dinamicamente aqui pelo JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ $requerimento->observacoes }}</textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success" id="submitEditBtn">
                                            <span class="button-text">Atualizar</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Armazenar os dados em elementos ocultos -->
    <div id="dadosExtra" style="display: none;">{{ json_encode($dadosExtra ?? []) }}</div>
    <div id="anexosAtuais" style="display: none;">{{ json_encode($anexosAtuais ?? []) }}</div>

    <div class="dropdown-backdrop" style="display: none;"></div>

    <script>
        // Carregar os dados dos elementos ocultos
        const dadosExtraElement = document.getElementById('dadosExtra');
        const anexosAtuaisElement = document.getElementById('anexosAtuais');

        window.dadosExtra = dadosExtraElement ? JSON.parse(dadosExtraElement.textContent) : [];
        window.anexosAtuais = anexosAtuaisElement ? JSON.parse(anexosAtuaisElement.textContent) : [];

        // Adicionar log para depuração
        console.log('dadosExtra:', window.dadosExtra);
        console.log('anexosAtuais:', window.anexosAtuais);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoRequisicao = document.getElementById('tipoRequisicao');
            const anexoDropdown = document.getElementById('anexoDropdown');
            const anexoDropdownMenu = document.getElementById('anexoDropdownMenu');
            const anexoButton = document.getElementById('anexoButton');
            const form = document.getElementById('applicationEditForm');
            const dropdownBackdrop = document.querySelector('.dropdown-backdrop');
            const isMobile = window.innerWidth <= 768;

            // Tipos de requerimento que precisam de informações adicionais ou anexos
            const tiposComAnexos = [1, 10, 15, 20, 21, 28, 30, 31, 32, 6, 13, 14, 19, 24];

            // Mapeamento de tipos de requisição para campos adicionais ou anexos
            const anexosPorTipo = {
                1: [{
                        label: "Declaração de Transferência",
                        name: "anexarArquivos[declaracao_transferencia]",
                        type: "file"
                    },
                    {
                        label: "Histórico Escolar do Ensino Fundamental (original)",
                        name: "anexarArquivos[historico_fundamental]",
                        type: "file"
                    },
                    {
                        label: "Histórico Escolar do Ensino Médio (original)",
                        name: "anexarArquivos[historico_medio]",
                        type: "file"
                    },
                    {
                        label: "Histórico Escolar do Ensino Superior (original)",
                        name: "anexarArquivos[historico_superior]",
                        type: "file"
                    },
                    {
                        label: "Ementas das disciplinas cursadas com Aprovação",
                        name: "anexarArquivos[ementas]",
                        type: "file"
                    }
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
                    options: ["Reintegração", "Estágio", "Entrega do Relatório de Estágio", "TCC"]
                }]
            };

            if (isMobile) {
                anexoButton.addEventListener('click', function() {
                    dropdownBackdrop.style.display = 'block';
                });
                
                dropdownBackdrop.addEventListener('click', function() {
                    dropdownBackdrop.style.display = 'none';
                    anexoDropdownMenu.classList.remove('show');
                });
            }

            // Carregar dados existentes
            const dadosExtra = window.dadosExtra;
            const anexosAtuais = window.anexosAtuais;

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
                    titleDiv.textContent = '{{ $requerimento->tipoRequisicao }}' || 'Informações Adicionais';
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
                    
                    anexosPorTipo[selectedType].forEach((field, index) => {
                        const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                        const fieldDiv = document.createElement('div');
                        fieldDiv.className = 'mb-3';

                        if (field.type === 'text') {
                            const existingValue = dadosExtra && dadosExtra[field.name.split('[')[1].replace(']', '')] || '';
                            fieldDiv.innerHTML = `
                                <label for="${uniqueId}" class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label} <span class="required-mark" style="color: #ff0000;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="${uniqueId}" name="${field.name}" value="${existingValue}" required>
                            `;
                        } else if (field.type === 'select') {
                            const existingValue = dadosExtra && dadosExtra[field.name.split('[')[1].replace(']', '')] || '';
                            let optionsHtml = '<option value="">Selecione</option>';
                            field.options.forEach(option => {
                                optionsHtml += `<option value="${option}" ${existingValue === option ? 'selected' : ''}>${option}</option>`;
                            });
                            fieldDiv.innerHTML = `
                                <label for="${uniqueId}" class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label} <span class="required-mark" style="color: #ff0000;">*</span></label>
                                <select class="form-select form-select-sm" id="${uniqueId}" name="${field.name}" required>
                                    ${optionsHtml}
                                </select>
                            `;
                        } else if (field.type === 'file') {
                            const anexoAtual = anexosAtuais[field.name.split('[')[1].replace(']', '')] || null;
                            fieldDiv.innerHTML = `
                                <div>
                                    <label class="form-label" style="font-size: 0.9rem; color: #000000;">${field.label}</label>
                                    ${anexoAtual ? `
                                        <div style="font-size: 0.85rem; color: #555;">
                                            Anexo atual: <a href="${anexoAtual.url}" target="_blank">${anexoAtual.name}</a>
                                        </div>
                                    ` : ''}
                                    <div class="mt-1">
                                        <input type="file" class="form-control form-control-sm file-input" id="${uniqueId}" name="${field.name}" accept=".pdf,.jpg,.png">
                                        <span id="file-name-${uniqueId}" style="font-size: 0.85rem; color: #555; display: block; margin-top: 0.25rem;">
                                            ${anexoAtual ? 'Manter anexo atual' : 'Nenhum arquivo selecionado'}
                                        </span>
                                    </div>
                                </div>
                            `;
                        }
                        containerDiv.appendChild(fieldDiv);
                    });
                    anexoDropdownMenu.appendChild(containerDiv);
                    initializeFileInputs();
                }
            }

            function initializeFileInputs() {
                const fileInputs = document.querySelectorAll('.file-input');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const uniqueId = input.id;
                        const fileNameSpan = document.getElementById(`file-name-${uniqueId}`);
                        if (input.files && input.files.length > 0) {
                            fileNameSpan.textContent = `Novo arquivo selecionado: ${input.files[0].name}`;
                        } else {
                            fileNameSpan.textContent = 'Nenhum arquivo selecionado';
                        }
                    });
                    
                    input.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                anexoDropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            function checkRequiredFields() {
                const requiredFields = ['orgaoExpedidor', 'campus', 'situacao', 'curso', 'periodo', 'turno'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    const requiredMark = document.getElementById(`${field}Required`);
                    if (input) {
                        if (input.tagName === 'SELECT') {
                            const isEmpty = !input.value || input.value === '';
                            requiredMark.style.display = isEmpty ? 'inline' : 'none';
                        } else if (input.type === 'text') {
                            const isEmpty = !input.value || input.value.trim() === '';
                            requiredMark.style.display = isEmpty ? 'inline' : 'none';
                        }
                    }
                });
            }

            form.addEventListener('submit', function(e) {
                let hasEmpty = false;
                const requiredFields = ['orgaoExpedidor', 'campus', 'situacao', 'curso', 'periodo', 'turno'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (input && (!input.value || input.value.trim() === '')) {
                        hasEmpty = true;
                        input.classList.add('is-invalid');
                    } else if (input) {
                        input.classList.remove('is-invalid');
                    }
                });

                const selectedType = Number(tipoRequisicao.value);
                if (tiposComAnexos.includes(selectedType)) {
                    anexosPorTipo[selectedType].forEach((field, index) => {
                        const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                        const input = document.getElementById(uniqueId);
                        if (input && (field.type === 'text' || field.type === 'select') && (!input.value || input.value.trim() === '')) {
                            hasEmpty = true;
                            input.classList.add('is-invalid');
                        } else if (input) {
                            input.classList.remove('is-invalid');
                        }
                    });
                }

                if (hasEmpty) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                } else {
                    const spinner = document.getElementById('global-loading-spinner');
                    if (spinner) {
                        spinner.classList.remove('hidden');
                    }
                    
                    const submitBtn = document.getElementById('submitEditBtn');
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
                }
            });

            updateAnexoDropdown();
            initializeFileInputs();
            checkRequiredFields();

            // Adicionar listeners para atualização dinâmica
            ['orgaoExpedidor', 'campus', 'situacao', 'curso', 'periodo', 'turno'].forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.addEventListener('change', checkRequiredFields);
                    input.addEventListener('input', checkRequiredFields);
                }
            });
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

        #anexoDropdownMenu {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: auto;
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
        
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
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
            
            #submitEditBtn {
                width: 100%;
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            
            .text-end {
                text-align: center !important;
                margin-top: 1rem;
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
        }
    </style>
</x-app-layout>