<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRE</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
    @vite('resources/css/app.css')
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Requerimento
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    <div class="bg-blue-200 overflow-hidden shadow-sm sm:rounded-lg border border-primary-200">
                        <div class="container mx-auto mt-3 md:mt-5">
                            <div class="p-4 md:p-6">
                                @if ($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                        <li class="text-red-700">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('application.update', $requerimento->id) }}" enctype="multipart/form-data" id="applicationEditForm" class="space-y-6" novalidate>
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                                        <div>
                                            <label for="nomeCompleto" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->nomeCompleto }}" readonly>
                                        </div>
                                        <div>
                                            <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->cpf }}" readonly>
                                        </div>
                                        <div>
                                            <label for="celular" class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->celular }}" readonly>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->email }}" readonly>
                                        </div>
                                        <div>
                                            <label for="rg" class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->rg }}" readonly>
                                        </div>
                                        <div>
                                            <label for="orgaoExpedidor" class="block text-sm font-medium text-gray-700 mb-1">Órgão Expedidor <span id="orgaoExpedidorRequired" class="text-red-500">*</span></label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="orgaoExpedidor" name="orgaoExpedidor" value="{{ $requerimento->orgaoExpedidor }}" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                                        <div>
                                            <label for="campus" class="block text-sm font-medium text-gray-700 mb-1">Campus <span id="campusRequired" class="text-red-500">*</span></label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="campus" name="campus" value="{{ $requerimento->campus }}" required>
                                        </div>
                                        <div>
                                            <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Número de Matrícula</label>
                                            <select class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" id="matricula_view" disabled>
                                                <option value="{{ $requerimento->matricula }}" selected>{{ $requerimento->matricula }}</option>
                                                @if(Auth::user()->second_matricula)
                                                <option value="{{ Auth::user()->second_matricula }}" {{ $requerimento->matricula === Auth::user()->second_matricula ? 'selected' : '' }}>{{ Auth::user()->second_matricula }}</option>
                                                @endif
                                            </select>
                                            <input type="hidden" name="matricula" value="{{ $requerimento->matricula }}">
                                        </div>
                                        <div>
                                            <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situação <span id="situacaoRequired" class="text-red-500">*</span></label>
                                            <select class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="situacao" name="situacao" required>
                                                <option value="1" {{ $requerimento->situacao === 'Matriculado' ? 'selected' : '' }}>Matriculado</option>
                                                <option value="2" {{ $requerimento->situacao === 'Graduado' ? 'selected' : '' }}>Graduado</option>
                                                <option value="3" {{ $requerimento->situacao === 'Desvinculado' ? 'selected' : '' }}>Desvinculado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                                        <div>
                                            <label for="curso" class="block text-sm font-medium text-gray-700 mb-1">Curso <span id="cursoRequired" class="text-red-500">*</span></label>
                                            <select class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="curso" name="curso" required>
                                                @foreach($cursos as $id => $nome)
                                                <option value="{{ $id }}" {{ $requerimento->curso === $nome ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Período <span id="periodoRequired" class="text-red-500">*</span></label>
                                            <select class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="periodo" name="periodo" required>
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ $requerimento->periodo == $i ? 'selected' : '' }}>{{ $i }}º</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label for="turno" class="block text-sm font-medium text-gray-700 mb-1">Turno <span id="turnoRequired" class="text-red-500">*</span></label>
                                            <select class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="turno" name="turno" required>
                                                <option value="manhã" {{ $requerimento->turno === 'manhã' ? 'selected' : '' }}>Manhã</option>
                                                <option value="tarde" {{ $requerimento->turno === 'tarde' ? 'selected' : '' }}>Tarde</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                        <div>
                                            <label for="tipoRequisicao" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Requisição</label>
                                            <input type="text" class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" value="{{ $requerimento->tipoRequisicao }}" readonly>
                                            <input type="hidden" id="tipoRequisicao" name="tipoRequisicao" value="{{ array_search($requerimento->tipoRequisicao, $tiposRequisicao) }}">
                                        </div>
                                        <div class="relative">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                            <div class="relative" id="anexoDropdown" style="margin-top: 0.5rem;">
                                                <button type="button" id="anexoButton" class="w-full text-left px-4 py-2 border border-gray-300 rounded-md  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                                    Anexos/informações (clique para abrir)
                                                </button>
                                                <div id="anexoDropdownMenu" class="hidden absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg p-4 border border-gray-200 max-h-80 overflow-y-auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                        <textarea class="w-full rounded-md border-gray-300  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="observacoes" name="observacoes" rows="3">{{ $requerimento->observacoes }}</textarea>
                                    </div>

                                    <div class="flex justify-end py-3 md:py-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" id="submitEditBtn">
                                            <span class="button-text">Atualizar</span>
                                            <svg class="hidden ml-2 -mr-1 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
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
    <div id="dadosExtra" class="hidden">{{ json_encode($dadosExtra ?? []) }}</div>
    <div id="anexosAtuais" class="hidden">{{ json_encode($anexosAtuais ?? []) }}</div>

    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="dropdown-backdrop"></div>
    <div id="notification-container" class="fixed top-4 right-4 z-50 max-w-md transform transition-transform duration-300 ease-in-out translate-x-full"></div>

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
            const dropdownBackdrop = document.getElementById('dropdown-backdrop');
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

            // Adicionar listeners para o dropdown
            anexoButton.addEventListener('click', function() {
                anexoDropdownMenu.classList.toggle('hidden');
                if (!anexoDropdownMenu.classList.contains('hidden') && isMobile) {
                    dropdownBackdrop.classList.remove('hidden');
                }
            });

            if (isMobile) {
                dropdownBackdrop.addEventListener('click', function() {
                    anexoDropdownMenu.classList.add('hidden');
                    dropdownBackdrop.classList.add('hidden');
                });
            }

            function updateAnexoDropdown() {
                const selectedType = Number(tipoRequisicao.value);
                anexoDropdown.style.display = 'none';
                anexoDropdownMenu.innerHTML = '';
                anexoDropdownMenu.classList.add('hidden');
                if (isMobile) {
                    dropdownBackdrop.classList.add('hidden');
                }

                if (tiposComAnexos.includes(selectedType)) {
                    anexoDropdown.style.display = 'block';

                    // Cabeçalho
                    const headerDiv = document.createElement('div');
                    headerDiv.className = 'text-gray-500 text-base font-medium pb-2 mb-3 border-b border-gray-200';
                    headerDiv.textContent = '{{ $requerimento->tipoRequisicao }}' || 'Informações Adicionais';
                    anexoDropdownMenu.appendChild(headerDiv);

                    // Container para os campos
                    const fieldsContainer = document.createElement('div');
                    fieldsContainer.className = 'space-y-4';

                    // Botão fechar para mobile
                    if (isMobile) {
                        const closeButton = document.createElement('button');
                        closeButton.className = 'w-full py-2 px-4 bg-gray-200 text-gray-700 rounded mb-4 text-sm font-medium';
                        closeButton.textContent = 'Fechar';
                        closeButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            anexoDropdownMenu.classList.add('hidden');
                            dropdownBackdrop.classList.add('hidden');
                        });
                        fieldsContainer.appendChild(closeButton);
                    }

                    // Adicionar campos
                    if (anexosPorTipo[selectedType]) {
                        anexosPorTipo[selectedType].forEach((field, index) => {
                            const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                            const fieldDiv = document.createElement('div');
                            fieldDiv.className = 'mb-4';

                            if (field.type === 'text') {
                                const existingValue = dadosExtra && dadosExtra[field.name.split('[')[1].replace(']', '')] || '';
                                fieldDiv.innerHTML = `
                                    <label for="${uniqueId}" class="block text-sm font-medium text-gray-700 mb-1">${field.label} <span class="text-red-500">*</span></label>
                                    <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" id="${uniqueId}" name="${field.name}" value="${existingValue}" required>
                                `;
                            } else if (field.type === 'select') {
                                const existingValue = dadosExtra && dadosExtra[field.name.split('[')[1].replace(']', '')] || '';
                                let optionsHtml = '<option value="">Selecione</option>';
                                field.options.forEach(option => {
                                    optionsHtml += `<option value="${option}" ${existingValue === option ? 'selected' : ''}>${option}</option>`;
                                });
                                fieldDiv.innerHTML = `
                                    <label for="${uniqueId}" class="block text-sm font-medium text-gray-700 mb-1">${field.label} <span class="text-red-500">*</span></label>
                                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" id="${uniqueId}" name="${field.name}" required>
                                        ${optionsHtml}
                                    </select>
                                `;
                            } else if (field.type === 'file') {
                                const anexoAtual = anexosAtuais[field.name.split('[')[1].replace(']', '')] || null;
                                fieldDiv.innerHTML = `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">${field.label}</label>
                                        ${anexoAtual ? `
                                            <div class="text-sm text-gray-600 mb-2">
                                                Anexo atual: <a href="${anexoAtual.url}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">${anexoAtual.name}</a>
                                            </div>
                                        ` : ''}
                                        <div class="mt-1">
                                            <div class="flex flex-col sm:flex-row items-start">
                                                <input type="file" class="hidden file-input" id="${uniqueId}" name="${field.name}" accept=".pdf,.jpg,.png">
                                                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 file-button" data-input-id="${uniqueId}">
                                                    Escolher arquivo
                                                </button>
                                                <span id="file-name-${uniqueId}" class="text-gray-500 text-sm mt-2 sm:mt-0 sm:ml-3 file-name">
                                                    ${anexoAtual ? 'Manter anexo atual' : 'Nenhum arquivo selecionado'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                            fieldsContainer.appendChild(fieldDiv);
                        });
                    }
                    anexoDropdownMenu.appendChild(fieldsContainer);
                    initializeFileInputs();
                }
            }

            function initializeFileInputs() {
                const fileInputs = document.querySelectorAll('.file-input');
                fileInputs.forEach(input => {
                    const uniqueId = input.id;
                    const fileButton = document.querySelector(`.file-button[data-input-id="${uniqueId}"]`);
                    const fileNameSpan = document.getElementById(`file-name-${uniqueId}`);

                    fileButton.addEventListener('click', (e) => {
                        e.stopPropagation();
                        input.click();
                    });

                    input.addEventListener('change', (e) => {
                        e.stopPropagation();
                        if (input.files && input.files.length > 0) {
                            fileNameSpan.textContent = `Novo arquivo selecionado: ${input.files[0].name}`;
                        } else {
                            fileNameSpan.textContent = 'Nenhum arquivo selecionado';
                        }
                    });

                    input.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                });

                anexoDropdownMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }

            document.addEventListener('click', function(e) {
                if (!anexoDropdownMenu.classList.contains('hidden') && 
                    !anexoDropdownMenu.contains(e.target) && 
                    e.target !== anexoButton &&
                    !anexoButton.contains(e.target)) {
                    anexoDropdownMenu.classList.add('hidden');
                    if (isMobile) {
                        dropdownBackdrop.classList.add('hidden');
                    }
                }
            });

            function checkRequiredFields() {
                const requiredFields = ['orgaoExpedidor', 'campus', 'situacao', 'curso', 'periodo', 'turno'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    const requiredMark = document.getElementById(`${field}Required`);
                    if (input && requiredMark) {
                        if (input.tagName === 'SELECT') {
                            const isEmpty = !input.value || input.value === '';
                            requiredMark.classList.toggle('hidden', !isEmpty);
                        } else if (input.type === 'text') {
                            const isEmpty = !input.value || input.value.trim() === '';
                            requiredMark.classList.toggle('hidden', !isEmpty);
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
                        input.classList.add('border-red-500', 'ring-red-200');
                    } else if (input) {
                        input.classList.remove('border-red-500', 'ring-red-200');
                    }
                });

                const selectedType = Number(tipoRequisicao.value);
                if (tiposComAnexos.includes(selectedType)) {
                    anexosPorTipo[selectedType].forEach((field, index) => {
                        const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                        const input = document.getElementById(uniqueId);
                        if (input && (field.type === 'text' || field.type === 'select') && (!input.value || input.value.trim() === '')) {
                            hasEmpty = true;
                            input.classList.add('border-red-500', 'ring-red-200');
                        } else if (input) {
                            input.classList.remove('border-red-500', 'ring-red-200');
                        }
                    });
                }

                if (hasEmpty) {
                    e.preventDefault();
                    showNotification('Por favor, preencha todos os campos obrigatórios, incluindo os anexos ou informações adicionais, se aplicável.', 'error');
                } else {
                    const submitBtn = document.getElementById('submitEditBtn');
                    if (submitBtn) {
                        submitBtn.setAttribute('disabled', 'disabled');
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        const buttonText = submitBtn.querySelector('.button-text');
                        const buttonSpinner = submitBtn.querySelector('svg');

                        if (buttonText && buttonSpinner) {
                            buttonText.textContent = 'Atualizando...';
                            buttonSpinner.classList.remove('hidden');
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

    <script>
        function showNotification(message, type = 'info', duration = 5000) {
            const container = document.getElementById('notification-container');
            
            let config = {
                info: {
                    bg: 'bg-blue-50',
                    border: 'border-blue-400',
                    text: 'text-blue-800',
                    ring: 'ring-blue-500',
                    icon: '<svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
                },
                success: {
                    bg: 'bg-green-50',
                    border: 'border-green-400',
                    text: 'text-green-800',
                    ring: 'ring-green-500',
                    icon: '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                },
                warning: {
                    bg: 'bg-yellow-50',
                    border: 'border-yellow-400',
                    text: 'text-yellow-800',
                    ring: 'ring-yellow-500',
                    icon: '<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
                },
                error: {
                    bg: 'bg-red-50',
                    border: 'border-red-400',
                    text: 'text-red-800',
                    ring: 'ring-red-500',
                    icon: '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                }
            };
            
            const currentConfig = config[type] || config.info;
            
            const notification = document.createElement('div');
            notification.className = `${currentConfig.bg} border-l-4 ${currentConfig.border} p-4 mb-3 shadow-md rounded-r opacity-0 transition-all duration-300 ease-in-out`;
            
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        ${currentConfig.icon}
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="${currentConfig.text} text-sm">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex ${currentConfig.text} rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-${type === 'info' ? 'blue' : type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-500">
                                <span class="sr-only">Fechar</span>
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                container.classList.remove('translate-x-full');
                notification.classList.remove('opacity-0');
                notification.classList.add('opacity-100');
            }, 10);
            
            const closeButton = notification.querySelector('button');
            closeButton.addEventListener('click', () => {
                fadeOutNotification(notification, container);
            });
            
            setTimeout(() => {
                fadeOutNotification(notification, container);
            }, duration);
        }
        
        function fadeOutNotification(notification, container) {
            notification.classList.remove('opacity-100');
            notification.classList.add('opacity-0');
            
            setTimeout(() => {
                notification.remove();
                if (container.children.length === 0) {
                    container.classList.add('translate-x-full');
                }
            }, 300);
        }

        function showLoading() {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loading-overlay';
                loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                loadingOverlay.innerHTML = `
                    <div class="loading-spinner"></div>
                `;
                document.body.appendChild(loadingOverlay);
            }

            function hideLoading() {
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.remove();
                }
            }

            document.getElementById('applicationForm').addEventListener('submit', function(e) {
                showLoading();
                
                hideLoading();
            });
    </script>
</x-app-layout>