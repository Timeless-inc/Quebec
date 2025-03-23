<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRE | Timeless</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/annexButton.js') }}"></script>
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

                                    <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" id="applicationForm" novalidate>
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="nomeCompleto" class="form-label">Nome Completo <span id="nomeCompletoRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="{{ Auth::user()->name }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="cpf" class="form-label">CPF <span id="cpfRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="celular" class="form-label">Celular <span id="celularRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="celular" name="celular" value="{{ Auth::user()->celular }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="email" class="form-label">Email <span id="emailRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="rg" class="form-label">RG <span id="rgRequired" class="required-mark" style="display: none; color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="rg" name="rg" value="{{ Auth::user()->rg }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="orgaoExpedidor" class="form-label">Órgão Expedidor <span id="orgaoExpedidorRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="orgaoExpedidor" name="orgaoExpedidor">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="campus" class="form-label">Campus <span id="campusRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="campus" name="campus">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="matricula" class="form-label">Número de Matrícula <span id="matriculaRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <input type="text" class="form-control" id="matricula" name="matricula" value="{{ Auth::user()->matricula }}" required>
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
                                            <div class="col-md-4">
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
                                            <div class="col-md-4">
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
                                            <div class="col-md-6">
                                                <label for="tipoRequisicao" class="form-label">Tipo de Requisição <span id="tipoRequisicaoRequired" class="required-mark" style="color: #ff0000;">*</span></label>
                                                <select class="form-select" id="tipoRequisicao" name="tipoRequisicao" onchange="updateAnexoDropdown()">
                                                    <option value="">Selecione o tipo de requisição</option>
                                                    @foreach($tiposRequisicao as $id => $tipo)
                                                    <option value="{{ $id }}" @if(in_array($id, $tiposComEventos ?? [])) data-event-required="true" @endif>
                                                        {{ $tipo }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">
                                                    Nota: Alguns tipos de requerimento só estão disponíveis em períodos específicos.
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="dropdown" id="anexoDropdown" style="display: none; margin-top: 2rem;">
                                                    <button class="form-select" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="anexoButton" style="text-align: left;">
                                                        Anexos/informações (clique para abrir)
                                                    </button>
                                                    <div class="dropdown-menu p-2" id="anexoDropdownMenu" style="background-color: #f8f9fa; border-radius: 0.375rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: auto; max-width: 600px; min-width: 0; overflow-x: auto;">
                                                        <!-- Campos de anexo serão gerados dinamicamente aqui pelo JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            const anexoDropdown = document.getElementById('anexoDropdown');
            const anexoDropdownMenu = document.getElementById('anexoDropdownMenu');
            const anexoButton = document.getElementById('anexoButton');
            const form = document.getElementById('applicationForm');

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
                    anexosPorTipo[selectedType].forEach((field, index) => {
                        const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`; // Nome único para evitar conflitos
                        const fieldDiv = document.createElement('div');
                        fieldDiv.className = 'mb-2';

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
                            <input type="file" class="form-control form-control-sm file-input" id="${uniqueId}" name="${field.name}" accept=".pdf,.jpg,.png" required>
                        `;
                        }
                        containerDiv.appendChild(fieldDiv);
                    });
                    anexoDropdownMenu.appendChild(containerDiv);
                    initializeFileInputs(); // Re-inicializa os eventos de arquivo
                }
            }

            function updateFileLabel(input) {
                const uniqueId = input.id;
                const label = document.querySelector(`label[for="${uniqueId}"]`);
                if (input.files && input.files.length > 0) {
                    label.textContent = `${input.files[0].name} (anexado)`;
                } else {
                    label.textContent = input.previousElementSibling.textContent; // Restaura o texto original
                }
            }

            function initializeFileInputs() {
                const fileInputs = document.querySelectorAll('.file-input');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        updateFileLabel(this);
                    });
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
            /* Altura máxima do dropdown */
            overflow-y: auto;
            /* Rolagem vertical quando ultrapassa a altura */
            overflow-x: auto;
            /* Mantém rolagem horizontal, se necessário */
            word-wrap: break-word;
            width: auto;
            max-width: 600px;
            min-width: 0;
        }

        @media (max-width: 768px) {
            #anexoDropdownMenu {
                max-width: 100%;
            }
        }

        .is-invalid {
            border-color: #ff0000 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25) !important;
        }

        .required-mark {
            margin-left: 4px;
        }
    </style>
</body>

</html>