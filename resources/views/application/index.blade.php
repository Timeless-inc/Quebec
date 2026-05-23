<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRE</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/annexButton.js') }}"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-1">
                    <h2 class="text-xl font-semibold leading-tight text-slate-900">
                        Novo requerimento
                    </h2>
                    <p class="text-sm text-slate-500">Preencha os dados abaixo para registrar sua solicitação.</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <span class="font-medium text-slate-800">Campos com *</span> são obrigatórios.
                </div>
            </div>
        </x-slot>
        @php
            $fieldClass = 'block h-10 w-full rounded-md border-slate-300 bg-white py-2 text-sm leading-5 text-slate-900 transition placeholder:text-slate-400 focus:border-green-600 focus:ring-green-600';
            $readonlyClass = 'block h-10 w-full cursor-not-allowed rounded-md border-slate-200 bg-slate-50 py-2 text-sm leading-5 text-slate-500 transition focus:border-slate-300 focus:ring-0';
            $textareaClass = 'block w-full min-h-28 rounded-md border-slate-300 bg-white text-sm text-slate-900 transition placeholder:text-slate-400 focus:border-green-600 focus:ring-green-600';
            $labelClass = 'mb-1.5 flex h-5 items-center text-xs font-semibold uppercase text-slate-600';
            $sectionTitleClass = 'border-l-4 border-green-600 pl-3 text-sm font-semibold uppercase text-green-700';
            $autoBadgeClass = 'ml-1 inline-flex h-4 items-center rounded bg-slate-100 px-1.5 text-[10px] font-semibold uppercase text-slate-400';
        @endphp

        <div class="py-8 md:py-10">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div class="text-slate-900">
                        @if ($errors->any())
                        <div class="mx-5 mt-5 rounded-md border border-red-200 bg-red-50 p-4 sm:mx-6">
                            <p class="text-sm font-semibold text-red-800">Revise os pontos abaixo:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" id="applicationForm" class="divide-y divide-slate-200" novalidate>
                            @csrf

                            <section class="px-5 py-6 sm:px-6">
                                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h4 class="{{ $sectionTitleClass }}">Dados pessoais</h4>
                                        <p class="mt-1 pl-4 text-xs text-slate-400">Informações principais vinculadas ao seu usuário.</p>
                                    </div>
                                    <p class="text-xs text-slate-400">Preenchido automaticamente pelo sistema</p>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <div>
                                        <label for="nomeCompleto" class="{{ $labelClass }}">Nome Completo <span class="{{ $autoBadgeClass }}">Auto</span> <span id="nomeCompletoRequired" class="hidden text-red-500">*</span></label>
                                        <input type="text" class="{{ $readonlyClass }}" id="nomeCompleto" name="nomeCompleto" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div>
                                        <label for="cpf" class="{{ $labelClass }}">CPF <span class="{{ $autoBadgeClass }}">Auto</span> <span id="cpfRequired" class="hidden text-red-500">*</span></label>
                                        <input type="text" class="{{ $readonlyClass }}" id="cpf" name="cpf" value="{{ Auth::user()->cpf }}" readonly>
                                    </div>
                                    <div>
                                        <label for="celular" class="{{ $labelClass }}">Celular <span id="celularRequired" class="text-red-500">*</span></label>
                                        <input type="text" class="{{ $fieldClass }}" id="celular" name="celular" value="{{ old('celular', $celular ?? '') }}" placeholder="(81) 9 8694-5453" required>
                                        <p id="celularError" class="mt-1 hidden text-sm text-red-600">Informe um número de celular válido com 11 dígitos (somente números).</p>
                                    </div>
                                    <div>
                                        <label for="email" class="{{ $labelClass }}">E-mail <span class="{{ $autoBadgeClass }}">Auto</span> <span id="emailRequired" class="hidden text-red-500">*</span></label>
                                        <input type="email" class="{{ $readonlyClass }}" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                    <div>
                                        <label for="rg" class="{{ $labelClass }}">RG <span class="{{ $autoBadgeClass }}">Auto</span> <span id="rgRequired" class="hidden text-red-500">*</span></label>
                                        <input type="text" class="{{ $readonlyClass }}" id="rg" name="rg" value="{{ Auth::user()->rg }}" readonly>
                                    </div>
                                    <div>
                                        <label for="orgaoExpedidor" class="{{ $labelClass }}">Órgão Expedidor <span id="orgaoExpedidorRequired" class="text-red-500">*</span></label>
                                        <input type="text" class="{{ $fieldClass }}" id="orgaoExpedidor" name="orgaoExpedidor" value="{{ old('orgaoExpedidor', $orgaoExpedidor ?? '') }}" placeholder="ex: SSP/PE">
                                    </div>
                                </div>
                            </section>

                            <section class="px-5 py-6 sm:px-6">
                                <div class="mb-4">
                                    <h4 class="{{ $sectionTitleClass }}">Dados acadêmicos</h4>
                                    <p class="mt-1 pl-4 text-xs text-slate-400">Informe o vínculo acadêmico relacionado a este requerimento.</p>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <div>
                                        <label for="campus" class="{{ $labelClass }}">Campus <span id="campusRequired" class="text-red-500">*</span></label>
                                        <input type="text" class="{{ $fieldClass }}" id="campus" name="campus" value="{{ old('campus', $campus ?? '') }}" placeholder="ex: Igarassu">
                                    </div>
                                    <div>
                                        <label for="matricula" class="{{ $labelClass }}">Número de Matrícula <span id="matriculaRequired" class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="matricula" name="matricula">
                                            <option value="">Selecione uma matrícula</option>
                                            <option value="{{ Auth::user()->matricula }}" {{ old('matricula', $matricula ?? '') == Auth::user()->matricula ? 'selected' : '' }}>{{ Auth::user()->matricula }}</option>
                                            @if(Auth::user()->second_matricula)
                                            <option value="{{ Auth::user()->second_matricula }}" {{ old('matricula', $matricula ?? '') == Auth::user()->second_matricula ? 'selected' : '' }}>{{ Auth::user()->second_matricula }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label for="situacao" class="{{ $labelClass }}">Situação <span id="situacaoRequired" class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="situacao" name="situacao">
                                            <option value="">Escolha</option>
                                            <option value="1" {{ old('situacao', $situacao ?? '') == 1 ? 'selected' : '' }}>Matriculado</option>
                                            <option value="2" {{ old('situacao', $situacao ?? '') == 2 ? 'selected' : '' }}>Graduado</option>
                                            <option value="3" {{ old('situacao', $situacao ?? '') == 3 ? 'selected' : '' }}>Desvinculado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="curso" class="{{ $labelClass }}">Curso <span id="cursoRequired" class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="curso" name="curso">
                                            <option value="">Escolha</option>
                                            <option value="1" {{ old('curso', $curso ?? '') == 1 ? 'selected' : '' }}>Administração</option>
                                            <option value="2" {{ old('curso', $curso ?? '') == 2 ? 'selected' : '' }}>Sistemas para Internet</option>
                                            <option value="3" {{ old('curso', $curso ?? '') == 3 ? 'selected' : '' }}>Logística</option>
                                            <option value="4" {{ old('curso', $curso ?? '') == 4 ? 'selected' : '' }}>Gestão de Qualidade</option>
                                            <option value="5" {{ old('curso', $curso ?? '') == 5 ? 'selected' : '' }}>Informática para Internet</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="periodo" class="{{ $labelClass }}">Período <span id="periodoRequired" class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="periodo" name="periodo">
                                            <option value="">Escolha</option>
                                            <option value="1" {{ old('periodo', $periodo ?? '') == 1 ? 'selected' : '' }}>1º</option>
                                            <option value="2" {{ old('periodo', $periodo ?? '') == 2 ? 'selected' : '' }}>2º</option>
                                            <option value="3" {{ old('periodo', $periodo ?? '') == 3 ? 'selected' : '' }}>3º</option>
                                            <option value="4" {{ old('periodo', $periodo ?? '') == 4 ? 'selected' : '' }}>4º</option>
                                            <option value="5" {{ old('periodo', $periodo ?? '') == 5 ? 'selected' : '' }}>5º</option>
                                            <option value="6" {{ old('periodo', $periodo ?? '') == 6 ? 'selected' : '' }}>6º</option>
                                            <option value="7" {{ old('periodo', $periodo ?? '') == 7 ? 'selected' : '' }}>7º</option>
                                            <option value="8" {{ old('periodo', $periodo ?? '') == 8 ? 'selected' : '' }}>8º</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="turno" class="{{ $labelClass }}">Turno <span id="turnoRequired" class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="turno" name="turno">
                                            <option value="">Escolha</option>
                                            <option value="manhã" {{ old('turno', $turno ?? '') == 'manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="tarde" {{ old('turno', $turno ?? '') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                                        </select>
                                    </div>
                                </div>
                            </section>

                            <section class="px-5 py-6 sm:px-6">
                                <div class="mb-4">
                                    <h4 class="{{ $sectionTitleClass }}">Detalhes do requerimento</h4>
                                    <p class="mt-1 pl-4 text-xs text-slate-400">Escolha o tipo de solicitação e adicione documentos ou observações quando necessário.</p>
                                </div>

                                <div class="space-y-5">
                                    <div>
                                        <label for="tipoRequisicao" class="{{ $labelClass }}">Tipo de Requisição <span class="text-red-500">*</span></label>
                                        <select class="{{ $fieldClass }}" id="tipoRequisicao" name="tipoRequisicao" required>
                                            <option value="">Selecione o tipo de requisição</option>

                                            <!-- Tipos disponíveis -->
                                            @foreach($tiposRequisicao as $id => $tipo)
                                            <option value="{{ $id }}">{{ $tipo }}</option>
                                            @endforeach

                                            <!-- Tipos indisponíveis (em vermelho) -->
                                            @if(isset($tiposIndisponiveis) && count($tiposIndisponiveis) > 0)
                                            <optgroup label="Indisponíveis no momento">
                                                @foreach($tiposIndisponiveis as $id => $tipo)
                                                <option value="{{ $id }}" class="text-red-500 italic" disabled>
                                                    {{ $tipo }} (Indisponível)
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        </select>
                                        <p class="mt-2 text-xs text-slate-500">
                                            Alguns tipos de requerimento só estão disponíveis durante períodos específicos.
                                        </p>
                                    </div>

                                    <div class="relative">
                                        <div class="hidden" id="anexoDropdown">
                                            <label class="{{ $labelClass }}">Documentos e informações</label>
                                            <button type="button" id="anexoButton" aria-expanded="false" class="flex h-10 w-full items-center justify-between rounded-md border border-slate-300 bg-white px-4 text-left text-sm font-medium text-slate-700 transition hover:border-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                                                <span>Anexos e informações</span>
                                                <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div id="anexoDropdownMenu" class="relative z-50 mt-3 hidden max-h-96 w-full overflow-y-auto rounded-md border border-slate-200 bg-white p-4">
                                            </div>
                                            <p id="anexoError" class="mt-2 hidden text-sm text-red-600">Arquivo obrigatório faltando ou excede o tamanho máximo permitido. Por favor, preencha todos os anexos necessários.</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="observacoes" class="{{ $labelClass }}">Observações</label>
                                        <textarea class="{{ $textareaClass }}" id="observacoes" name="observacoes" rows="4">{{ old('observacoes') }}</textarea>
                                    </div>
                                </div>
                            </section>

                            <div class="flex flex-col-reverse gap-3 bg-slate-50 px-5 py-5 sm:flex-row sm:items-center sm:justify-end sm:px-6">
                                <a href="{{ route('dashboard') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                                    Cancelar
                                </a>
                                <button type="submit" class="inline-flex h-10 items-center justify-center gap-2 rounded-md border border-transparent bg-green-600 px-5 text-sm font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2" id="submitBtn">
                                    <span class="button-text">Enviar requerimento</span>
                                    <svg class="hidden h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
    </x-app-layout>

    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="dropdown-backdrop"></div>

    <div id="notification-container" class="fixed top-4 right-4 z-50 max-w-md transform transition-transform duration-300 ease-in-out translate-x-full"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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

        document.addEventListener('DOMContentLoaded', function() {
            // apply mask to celular (format: (00) 0 0000-0000)
            const celularInput = document.getElementById('celular');
            function applyCelularMask() {
                if (window.jQuery && jQuery().mask) {
                    try {
                        jQuery(function($){
                            $('#celular').mask('(00) 0 0000-0000');
                        });
                    } catch (e) {
                        // ignore
                    }
                }
            }
            applyCelularMask();

            function validateCelular() {
                if (!celularInput) return true;
                const raw = celularInput.value.replace(/\D/g, '');
                const errorEl = document.getElementById('celularError');
                if (raw.length !== 11) {
                    celularInput.classList.add('border-red-500', 'ring-red-200');
                    if (errorEl) {
                        errorEl.classList.remove('hidden');
                        errorEl.textContent = 'Informe um número de celular válido com 11 dígitos (ex: (81) 9 8694-5453).';
                    }
                    return false;
                }
                celularInput.classList.remove('border-red-500', 'ring-red-200');
                if (errorEl) errorEl.classList.add('hidden');
                return true;
            }

            if (celularInput) {
                celularInput.addEventListener('input', function() {
                    // keep only digits for validation
                    const pos = this.selectionStart;
                    const before = this.value;
                    applyCelularMask();
                    validateCelular();
                });
            }
            const tipoRequisicao = document.getElementById('tipoRequisicao');
            const anexoDropdown = document.getElementById('anexoDropdown');
            const anexoDropdownMenu = document.getElementById('anexoDropdownMenu');
            const anexoButton = document.getElementById('anexoButton');
            const dropdownBackdrop = document.getElementById('dropdown-backdrop');
            const form = document.getElementById('applicationForm');
            const selectElement = document.getElementById('tipoRequisicao');
            const options = selectElement.querySelectorAll('option');
            const isMobile = window.innerWidth <= 768;

            anexoButton.addEventListener('click', function(e) {
                e.stopPropagation(); 
                anexoDropdownMenu.classList.toggle('hidden');
                anexoButton.setAttribute('aria-expanded', anexoDropdownMenu.classList.contains('hidden') ? 'false' : 'true');

                if (!anexoDropdownMenu.classList.contains('hidden')) {
                    setTimeout(() => {
                        const firstInteractive = anexoDropdownMenu.querySelector('input, select, button:not([aria-hidden="true"])');
                        if (firstInteractive) {
                            firstInteractive.focus();
                        }
                    }, 100);
                }
                    refreshAnexoValidation();
            });

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

                anexoDropdown.classList.add('hidden');
                anexoDropdownMenu.innerHTML = '';
                anexoDropdownMenu.classList.add('hidden');
                anexoButton.setAttribute('aria-expanded', 'false');
                if (isMobile) {
                    dropdownBackdrop.classList.add('hidden');
                }

                if (tiposComAnexos.includes(selectedType)) {
                    anexoDropdown.classList.remove('hidden');

                    const headerDiv = document.createElement('div');
                    headerDiv.className = 'border-b border-slate-200 pb-3 text-sm font-semibold text-slate-900';
                    const tipoDescricao = tipoRequisicao.options[tipoRequisicao.selectedIndex].text;
                    headerDiv.textContent = tipoDescricao || 'Informações Adicionais';
                    anexoDropdownMenu.appendChild(headerDiv);

                    const fieldsContainer = document.createElement('div');
                    fieldsContainer.className = 'space-y-4';

                    if (isMobile) {
                        const closeButton = document.createElement('button');
                        closeButton.className = 'mb-4 inline-flex w-full items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700';
                        closeButton.textContent = 'Fechar';
                        closeButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            anexoDropdownMenu.classList.add('hidden');
                            anexoButton.setAttribute('aria-expanded', 'false');
                            dropdownBackdrop.classList.add('hidden');
                        });
                        fieldsContainer.appendChild(closeButton);
                    }

                    // Adicionar campos
                    if (anexosPorTipo[selectedType]) {
                        anexosPorTipo[selectedType].forEach((field, index) => {
                            const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                            const fieldDiv = document.createElement('div');
                            fieldDiv.className = 'rounded-md border border-slate-200 bg-slate-50 p-3';

                            if (field.type === 'text') {
                                fieldDiv.innerHTML = `
                                    <label for="${uniqueId}" class="mb-1.5 flex h-5 items-center text-xs font-semibold uppercase text-slate-600">${field.label} <span class="text-red-500">*</span></label>
                                    <input type="text" class="block h-10 w-full rounded-md border-slate-300 bg-white py-2 text-sm leading-5 text-slate-900 focus:border-green-600 focus:ring-green-600" id="${uniqueId}" name="${field.name}" required>
                                `;
                            } else if (field.type === 'select') {
                                let optionsHtml = '<option value="">Selecione</option>';
                                field.options.forEach(option => {
                                    optionsHtml += `<option value="${option}">${option}</option>`;
                                });
                                fieldDiv.innerHTML = `
                                    <label for="${uniqueId}" class="mb-1.5 flex h-5 items-center text-xs font-semibold uppercase text-slate-600">${field.label} <span class="text-red-500">*</span></label>
                                    <select class="block h-10 w-full rounded-md border-slate-300 bg-white py-2 text-sm leading-5 text-slate-900 focus:border-green-600 focus:ring-green-600" id="${uniqueId}" name="${field.name}" required>
                                        ${optionsHtml}
                                    </select>
                                `;
                            } else if (field.type === 'file') {
                                fieldDiv.innerHTML = `
                                    <label for="${uniqueId}" class="mb-1.5 flex h-5 items-center text-xs font-semibold uppercase text-slate-600">${field.label} <span class="text-red-500">*</span></label>
                                    <div class="space-y-3">
                                        <input type="file" class="hidden file-input" id="${uniqueId}" name="${field.name}" accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/jpeg,image/png,image/webp" required>
                                        <p class="text-xs leading-5 text-slate-500">Imagens até 5 MB, PDF até 2 MB. Tamanho total por envio: 10 MB.</p>
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        <button type="button" class="inline-flex h-10 items-center justify-center rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 file-button" data-input-id="${uniqueId}">
                                            Escolher arquivo
                                        </button>
                                        <span id="file-name-${uniqueId}" class="min-w-0 break-words text-sm text-slate-500 file-name">Nenhum arquivo selecionado</span>
                                        </div>
                                    </div>
                                    <p id="error_${uniqueId}" class="mt-2 hidden text-sm text-red-600">Este arquivo é obrigatório.</p>
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
                    const errorEl = document.getElementById(`error_${uniqueId}`);

                    if (!fileButton) return;

                    fileButton.addEventListener('click', (e) => {
                        e.stopPropagation();
                        input.click();
                    });

                    input.addEventListener('change', (e) => {
                        e.stopPropagation();
                        if (input.files && input.files.length > 0) {
                            if (fileNameSpan) fileNameSpan.textContent = input.files[0].name;
                            if (errorEl) errorEl.classList.add('hidden');
                            fileButton.classList.remove('border-red-500', 'bg-red-50', 'text-red-700');
                        } else {
                            if (fileNameSpan) fileNameSpan.textContent = 'Nenhum arquivo selecionado';
                        }
                        refreshAnexoValidation();
                    });

                    input.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                });

                anexoDropdownMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
                
                const focusableElements = anexoDropdownMenu.querySelectorAll('button, input, select, textarea');
                focusableElements.forEach(element => {
                    element.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                });
            }

            function refreshAnexoValidation() {
                const selectedType = Number(tipoRequisicao.value);
                const anexoErrorEl = document.getElementById('anexoError');
                const camposDoTipo = anexosPorTipo[selectedType] || [];
                let missingRequired = false;

                camposDoTipo.forEach((field, index) => {
                    if (field.type !== 'file') {
                        return;
                    }

                    const uniqueId = `${field.name.replace(/[\[\]]/g, '_')}_${index}`;
                    const input = document.getElementById(uniqueId);
                    const fileButton = document.querySelector(`.file-button[data-input-id="${uniqueId}"]`);
                    const errorEl = document.getElementById(`error_${uniqueId}`);

                    if (!input) return;

                    const isMissing = !input.files || input.files.length === 0;
                    if (isMissing) {
                        missingRequired = true;
                        if (fileButton) fileButton.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
                        if (errorEl) errorEl.classList.remove('hidden');
                    } else {
                        if (fileButton) fileButton.classList.remove('border-red-500', 'bg-red-50', 'text-red-700');
                        if (errorEl) errorEl.classList.add('hidden');
                    }
                });

                if (tiposComAnexos.includes(selectedType) && missingRequired) {
                    anexoButton.classList.add('border-red-500', 'ring-red-200');
                    if (anexoErrorEl) anexoErrorEl.classList.remove('hidden');
                } else {
                    anexoButton.classList.remove('border-red-500', 'ring-red-200');
                    if (anexoErrorEl) anexoErrorEl.classList.add('hidden');
                }

                return missingRequired;
            }

            function checkRequiredFields() {
                const requiredFields = [
                    'celular', 'orgaoExpedidor', 'campus', 'matricula', 'situacao',
                    'curso', 'periodo', 'turno', 'tipoRequisicao'
                ];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    const requiredMark = document.getElementById(`${field}Required`);
                    if (input && requiredMark) {
                        requiredMark.classList.remove('hidden');
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
                    refreshAnexoValidation();
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
                                input.classList.add('border-red-500', 'ring-red-200');
                            } else {
                                input.classList.remove('border-red-500', 'ring-red-200');
                            }
                        } else if (input.type === 'text' || input.type === 'email') {
                            if (!input.value || input.value.trim() === '') {
                                hasEmpty = true;
                                input.classList.add('border-red-500', 'ring-red-200');
                            } else {
                                input.classList.remove('border-red-500', 'ring-red-200');
                            }
                        }
                    }
                });

                const anexosFaltando = refreshAnexoValidation();
                hasEmpty = hasEmpty || anexosFaltando;

                // Validação do formato do celular (11 dígitos numéricos)
                if (typeof validateCelular === 'function') {
                    if (!validateCelular()) {
                        hasEmpty = true;
                    }
                }

                if (hasEmpty) {
                    e.preventDefault();
                    showNotification('Por favor, preencha todos os campos obrigatórios, incluindo os anexos ou informações adicionais, se aplicável.', 'error');
                    return;
                }

                // Se passou na validação, normalizar celular
                try {
                    if (celularInput) {
                        celularInput.value = celularInput.value.replace(/\D/g, '');
                    }
                } catch (err) {
                    // ignore
                }

                // Desabilitar botão de envio
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    const buttonText = submitBtn.querySelector('.button-text');
                    const buttonSpinner = submitBtn.querySelector('svg');

                    if (buttonText && buttonSpinner) {
                        buttonText.textContent = 'Enviando...';
                        buttonSpinner.classList.remove('hidden');
                    }
                }
            });

            form.addEventListener('submit', function(event) {
                // strip formatting from celular before final submit so backend receives only digits
                try {
                    if (celularInput) {
                        celularInput.value = celularInput.value.replace(/\D/g, '');
                    }
                } catch (e) {
                    // ignore
                }

                if (document.querySelectorAll('.border-red-500').length > 0) {
                    return;
                }

                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    const buttonText = submitBtn.querySelector('.button-text');
                    const buttonSpinner = submitBtn.querySelector('svg');

                    if (buttonText && buttonSpinner) {
                        buttonText.textContent = 'Enviando...';
                        buttonSpinner.classList.remove('hidden');
                    }
                }
            });

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

            updateAnexoDropdown();
            initializeFileInputs();
            initializeFieldListeners();
            checkRequiredFields();
        });
    </script>
</body>
</html>
