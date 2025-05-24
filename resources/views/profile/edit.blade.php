<title>SRE - Perfil</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitar Alteração de Dados') }}
        </h2>
    </x-slot>

    <x-loading-spinner />

    <style>
        .validation-error {
            border-color: #ef4444 !important;
        }

        .validation-success {
            border-color: #10b981 !important;
        }

        .field-validated {
            position: relative;
        }

        .field-validated::after {
            content: '✓';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
        }

        .field-error::after {
            content: '✗';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #ef4444;
        }
        
        .responsive-table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .mobile-card {
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            background-color: white;
        }
        
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .mobile-card-body {
            padding: 0.5rem 0;
        }
        
        .mobile-row {
            display: flex;
            padding: 0.5rem 0;
            align-items: flex-start;
        }
        
        .mobile-label {
            font-weight: 500;
            flex: 0 0 40%;
            padding-right: 0.5rem;
        }
        
        .mobile-value {
            flex: 0 0 60%;
        }
        
        .desktop-view {
            display: block;
        }
        
        .mobile-view {
            display: none;
        }
        
        .data-item {
            margin-bottom: 0.75rem;
        }
        
        .data-item-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.25rem;
        }
        
        .w-full input, .w-full select, .w-full textarea {
            font-size: 16px !important; 
        }
        
        .history-table {
            font-size: 0.85rem;
        }
        
        .history-card {
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .history-header {
            background-color: #f9fafb;
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .history-content {
            padding: 0.75rem;
        }
        
        .history-field {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .history-field:last-child {
            border-bottom: none;
        }
        
        @media (max-width: 768px) {
            .desktop-view {
                display: none !important;
            }
            
            .mobile-view {
                display: block !important;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">
    <script src="{{ asset('js/form-loading.js') }}" defer></script>

    @if (session('status'))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('status') }}
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <!-- Formulário de Solicitação -->
                <div>
                    <h3 class="text-xl font-semibold">Solicitar Alteração de Dados</h3>
                    <p class="text-sm text-gray-600 mt-2">Para solicitar alteração de seus dados, selecione os campos que deseja alterar, preencha os novos valores e anexe os documentos comprobatórios.</p>

                    <form method="POST" action="{{ route('profile.request-update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div class="desktop-view responsive-table">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Atual</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Novo Valor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anexo Comprobatório</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Nome -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" id="change_name" name="fields[name][selected]" class="toggle-change w-4 h-4 text-red-600 rounded focus:ring-red-500" data-field="name">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">Nome</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Auth::user()->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="name_fields">
                                                <input id="name" name="fields[name][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo nome">
                                                <input type="hidden" name="fields[name][current]" value="{{ Auth::user()->name }}">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="name_document_fields">
                                                <input id="name_document" name="fields[name][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Matrícula Principal -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" id="change_matricula" name="fields[matricula][selected]" class="toggle-change w-4 h-4 text-red-600 rounded focus:ring-red-500" data-field="matricula">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">Matrícula Principal</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Auth::user()->matricula }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="matricula_fields">
                                                <input id="matricula" name="fields[matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" value="{{ Auth::user()->matricula }}" placeholder="Nova matrícula principal">
                                                <input type="hidden" name="fields[matricula][current]" value="{{ Auth::user()->matricula }}">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="matricula_document_fields">
                                                <input id="matricula_document" name="fields[matricula][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Segunda Matrícula -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" id="change_second_matricula" name="fields[second_matricula][selected]" class="toggle-change w-4 h-4 text-red-600 rounded focus:ring-red-500" data-field="second_matricula">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">Segunda Matrícula</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ Auth::user()->second_matricula ?? 'Não cadastrada' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="second_matricula_fields">
                                                @if(Auth::user()->second_matricula)
                                                    <!-- Se já tem segunda matrícula, exibe campos normais de alteração -->
                                                    <input id="second_matricula" name="fields[second_matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" value="{{ Auth::user()->second_matricula }}" placeholder="Nova segunda matrícula">
                                                    <input type="hidden" name="fields[second_matricula][current]" value="{{ Auth::user()->second_matricula }}">
                                                    
                                                    <div class="mt-2">
                                                        <label class="flex items-center">
                                                            <input type="checkbox" id="remove_second_matricula" name="fields[second_matricula][remove]" class="w-4 h-4 text-red-600 rounded focus:ring-red-500 mr-2">
                                                            <span class="text-sm text-red-600">Remover segunda matrícula</span>
                                                        </label>
                                                    </div>
                                                @else
                                                    <!-- Se não tem segunda matrícula, exibe apenas campo para adição -->
                                                    <input id="second_matricula" name="fields[second_matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Adicionar segunda matrícula">
                                                    <input type="hidden" name="fields[second_matricula][current]" value="">
                                                    <p class="text-sm text-gray-500 mt-1">Você adicionará uma segunda matrícula.</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="second_matricula_document_fields">
                                                <input id="second_matricula_document" name="fields[second_matricula][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- CPF -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" id="change_cpf" name="fields[cpf][selected]" class="toggle-change w-4 h-4 text-red-600 rounded focus:ring-red-500" data-field="cpf">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">CPF</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Auth::user()->cpf }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="cpf_fields">
                                                <input id="cpf" name="fields[cpf][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo CPF">
                                                <input type="hidden" name="fields[cpf][current]" value="{{ Auth::user()->cpf }}">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="cpf_document_fields">
                                                <input id="cpf_document" name="fields[cpf][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- RG -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" id="change_rg" name="fields[rg][selected]" class="toggle-change w-4 h-4 text-red-600 rounded focus:ring-red-500" data-field="rg">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">RG</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Auth::user()->rg }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="rg_fields">
                                                <input id="rg" name="fields[rg][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo RG">
                                                <input type="hidden" name="fields[rg][current]" value="{{ Auth::user()->rg }}">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="change-field hidden" id="rg_document_fields">
                                                <input id="rg_document" name="fields[rg][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-view">
                            <!-- Card do Nome -->
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <div class="font-medium">Nome</div>
                                    <input type="checkbox" id="mobile_change_name" name="fields[name][selected]" class="toggle-change w-5 h-5 text-red-600 rounded focus:ring-red-500" data-field="name">
                                </div>
                                <div class="mobile-card-body">
                                    <div class="data-item">
                                        <div class="data-item-label">Valor Atual:</div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                    
                                    <div class="change-field hidden" id="mobile_name_fields">
                                        <div class="data-item">
                                            <div class="data-item-label">Novo Valor:</div>
                                            <input id="mobile_name" name="fields[name][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo nome">
                                            <input type="hidden" name="fields[name][current]" value="{{ Auth::user()->name }}">
                                        </div>
                                        
                                        <div class="data-item">
                                            <div class="data-item-label">Anexo Comprobatório:</div>
                                            <input id="mobile_name_document" name="fields[name][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card da Matrícula Principal -->
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <div class="font-medium">Matrícula Principal</div>
                                    <input type="checkbox" id="mobile_change_matricula" name="fields[matricula][selected]" class="toggle-change w-5 h-5 text-red-600 rounded focus:ring-red-500" data-field="matricula">
                                </div>
                                <div class="mobile-card-body">
                                    <div class="data-item">
                                        <div class="data-item-label">Valor Atual:</div>
                                        <div>{{ Auth::user()->matricula }}</div>
                                    </div>
                                    
                                    <div class="change-field hidden" id="mobile_matricula_fields">
                                        <div class="data-item">
                                            <div class="data-item-label">Novo Valor:</div>
                                            <input id="mobile_matricula" name="fields[matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Nova matrícula principal">
                                            <input type="hidden" name="fields[matricula][current]" value="{{ Auth::user()->matricula }}">
                                        </div>
                                        
                                        <div class="data-item">
                                            <div class="data-item-label">Anexo Comprobatório:</div>
                                            <input id="mobile_matricula_document" name="fields[matricula][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card da Segunda Matrícula -->
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <div class="font-medium">Segunda Matrícula</div>
                                    <input type="checkbox" id="mobile_change_second_matricula" name="fields[second_matricula][selected]" class="toggle-change w-5 h-5 text-red-600 rounded focus:ring-red-500" data-field="second_matricula">
                                </div>
                                <div class="mobile-card-body">
                                    <div class="data-item">
                                        <div class="data-item-label">Valor Atual:</div>
                                        <div>{{ Auth::user()->second_matricula ?? 'Não cadastrada' }}</div>
                                    </div>
                                    
                                    <div class="change-field hidden" id="mobile_second_matricula_fields">
                                        <div class="data-item">
                                            <div class="data-item-label">Novo Valor:</div>
                                            @if(Auth::user()->second_matricula)
                                                <input id="mobile_second_matricula" name="fields[second_matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" value="{{ Auth::user()->second_matricula }}" placeholder="Nova segunda matrícula">
                                                <input type="hidden" name="fields[second_matricula][current]" value="{{ Auth::user()->second_matricula }}">
                                                
                                                <div class="mt-2">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" id="mobile_remove_second_matricula" name="fields[second_matricula][remove]" class="w-4 h-4 text-red-600 rounded focus:ring-red-500 mr-2">
                                                        <span class="text-sm text-red-600">Remover segunda matrícula</span>
                                                    </label>
                                                </div>
                                            @else
                                                <input id="mobile_second_matricula" name="fields[second_matricula][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Adicionar segunda matrícula">
                                                <input type="hidden" name="fields[second_matricula][current]" value="">
                                                <p class="text-sm text-gray-500 mt-1">Você adicionará uma segunda matrícula.</p>
                                            @endif
                                        </div>
                                        
                                        <div class="data-item">
                                            <div class="data-item-label">Anexo Comprobatório:</div>
                                            <input id="mobile_second_matricula_document" name="fields[second_matricula][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card do CPF -->
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <div class="font-medium">CPF</div>
                                    <input type="checkbox" id="mobile_change_cpf" name="fields[cpf][selected]" class="toggle-change w-5 h-5 text-red-600 rounded focus:ring-red-500" data-field="cpf">
                                </div>
                                <div class="mobile-card-body">
                                    <div class="data-item">
                                        <div class="data-item-label">Valor Atual:</div>
                                        <div>{{ Auth::user()->cpf }}</div>
                                    </div>
                                    
                                    <div class="change-field hidden" id="mobile_cpf_fields">
                                        <div class="data-item">
                                            <div class="data-item-label">Novo Valor:</div>
                                            <input id="mobile_cpf" name="fields[cpf][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo CPF">
                                            <input type="hidden" name="fields[cpf][current]" value="{{ Auth::user()->cpf }}">
                                        </div>
                                        
                                        <div class="data-item">
                                            <div class="data-item-label">Anexo Comprobatório:</div>
                                            <input id="mobile_cpf_document" name="fields[cpf][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card do RG -->
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <div class="font-medium">RG</div>
                                    <input type="checkbox" id="mobile_change_rg" name="fields[rg][selected]" class="toggle-change w-5 h-5 text-red-600 rounded focus:ring-red-500" data-field="rg">
                                </div>
                                <div class="mobile-card-body">
                                    <div class="data-item">
                                        <div class="data-item-label">Valor Atual:</div>
                                        <div>{{ Auth::user()->rg }}</div>
                                    </div>
                                    
                                    <div class="change-field hidden" id="mobile_rg_fields">
                                        <div class="data-item">
                                            <div class="data-item-label">Novo Valor:</div>
                                            <input id="mobile_rg" name="fields[rg][value]" type="text" class="block w-full border rounded-md px-3 py-2" placeholder="Novo RG">
                                            <input type="hidden" name="fields[rg][current]" value="{{ Auth::user()->rg }}">
                                        </div>
                                        
                                        <div class="data-item">
                                            <div class="data-item-label">Anexo Comprobatório:</div>
                                            <input id="mobile_rg_document" name="fields[rg][document]" type="file" class="block w-full text-sm border rounded-md px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações e Avisos -->
                        <div class="text-sm text-gray-600 mt-2 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                            <p class="font-semibold">⚠️ Importante:</p>
                            <ul class="list-disc ml-5 mt-1">
                                <li>Marque a caixa de seleção para cada dado que deseja alterar</li>
                                <li>É obrigatório anexar documento comprobatório para cada alteração solicitada</li>
                                <li>As alterações só serão efetivadas após análise e aprovação da CRADT</li>
                            </ul>
                        </div>

                        <!-- Botão de Solicitação -->
                        <div class="mt-6 flex justify-end">
                            <button type="submit" id="submitBtn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md transition w-full sm:w-auto" disabled>
                                Enviar Solicitação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Solicitações -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-semibold mb-4">Histórico de Solicitações</h3>

            <div class="desktop-view bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="responsive-table">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Atual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Novo Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observação</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Solicitação</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($profileRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->field == 'name') Nome
                                    @elseif($request->field == 'matricula') Matrícula
                                    @elseif($request->field == 'cpf') CPF
                                    @elseif($request->field == 'rg') RG
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->current_value }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->new_value }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                    @elseif($request->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprovado</span>
                                    @elseif($request->status == 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeitado</span>
                                    @elseif($request->status == 'needs_review')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Em Revisão</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->admin_comment ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhuma solicitação encontrada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mobile-view">
                @forelse($profileRequests as $request)
                <div class="history-card">
                    <div class="history-header">
                        <div class="flex justify-between items-center">
                            <div class="font-medium">
                                @if($request->field == 'name') Nome
                                @elseif($request->field == 'matricula') Matrícula
                                @elseif($request->field == 'cpf') CPF
                                @elseif($request->field == 'rg') RG
                                @endif
                            </div>
                            
                            <div>
                                @if($request->status == 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                @elseif($request->status == 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprovado</span>
                                @elseif($request->status == 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeitado</span>
                                @elseif($request->status == 'needs_review')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Em Revisão</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $request->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="history-content">
                        <div class="history-field">
                            <div class="text-sm text-gray-500">Valor Atual:</div>
                            <div class="text-sm font-medium">{{ $request->current_value }}</div>
                        </div>
                        
                        <div class="history-field">
                            <div class="text-sm text-gray-500">Novo Valor:</div>
                            <div class="text-sm font-medium">{{ $request->new_value }}</div>
                        </div>
                        
                        @if($request->admin_comment)
                        <div class="history-field">
                            <div class="text-sm text-gray-500">Observação:</div>
                            <div class="text-sm">{{ $request->admin_comment }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white shadow sm:rounded-lg p-4 text-center text-gray-500">
                    Nenhuma solicitação encontrada
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.toggle-change');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('global-loading-spinner');
            const profileForm = document.querySelector('form[action="{{ route("profile.request-update") }}"]');

            document.querySelectorAll('[id^="mobile_change_"]').forEach(mobileToggle => {
                const field = mobileToggle.getAttribute('data-field');
                const desktopToggle = document.getElementById(`change_${field}`);
                
                if (desktopToggle) {
                    desktopToggle.addEventListener('change', function() {
                        mobileToggle.checked = this.checked;
                        toggleFields(field, this.checked);
                    });
                    
                    mobileToggle.addEventListener('change', function() {
                        desktopToggle.checked = this.checked;
                        toggleFields(field, this.checked);
                    });
                }
            });

            function toggleFields(field, isChecked) {
                const valueFields = document.getElementById(`${field}_fields`);
                const documentFields = document.getElementById(`${field}_document_fields`);
                
                const mobileValueFields = document.getElementById(`mobile_${field}_fields`);
                
                if (valueFields) {
                    valueFields.classList.toggle('hidden', !isChecked);
                }
                
                if (documentFields) {
                    documentFields.classList.toggle('hidden', !isChecked);
                }
                
                if (mobileValueFields) {
                    mobileValueFields.classList.toggle('hidden', !isChecked);
                }
                
                updateSubmitButton();
            }

            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    const anyChecked = Array.from(toggles).some(toggle => toggle.checked);
                    if (!anyChecked) {
                        e.preventDefault();
                        return;
                    }
                    
                    spinner.classList.remove('hidden');
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.classList.add('opacity-75');
                    submitBtn.classList.remove('hover:bg-red-600');
                });
            }

            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const field = this.getAttribute('data-field');
                    toggleFields(field, this.checked);
                });
            });

            function updateSubmitButton() {
                const anyChecked = Array.from(toggles).some(toggle => toggle.checked);
                submitBtn.disabled = !anyChecked;

                if (anyChecked) {
                    submitBtn.classList.remove('bg-gray-400');
                    submitBtn.classList.add('bg-red-500', 'hover:bg-red-600');
                } else {
                    submitBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
                    submitBtn.classList.add('bg-gray-400');
                }
            }

            const fieldsToSync = ['name', 'matricula', 'second_matricula', 'cpf', 'rg'];
            
            fieldsToSync.forEach(field => {
                const desktopInput = document.getElementById(field);
                const mobileInput = document.getElementById(`mobile_${field}`);
                
                if (desktopInput && mobileInput) {
                    desktopInput.addEventListener('input', function() {
                        mobileInput.value = this.value;
                    });
                    
                    mobileInput.addEventListener('input', function() {
                        desktopInput.value = this.value;
                    });
                }
            });

            const fieldsToValidate = ['cpf', 'matricula', 'rg', 'mobile_cpf', 'mobile_matricula', 'mobile_rg'];

            fieldsToValidate.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.addEventListener('blur', function() {
                        if (this.value.trim() === '') return;

                        clearValidationMessage(field);
                        
                        const fieldName = field.replace('mobile_', '');

                        fetch(`/profile/check-duplicate?field=${fieldName}&value=${this.value}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.exists) {
                                    showValidationMessage(field, `Este ${getFieldLabel(fieldName)} já está registrado para outro usuário.`);
                                }
                            })
                            .catch(error => console.error('Erro na verificação:', error));
                    });
                }
            });

            function clearValidationMessage(field) {
                const existingMessage = document.getElementById(`${field}_validation_message`);
                if (existingMessage) {
                    existingMessage.remove();
                }
            }

            function showValidationMessage(field, message) {
                const input = document.getElementById(field);
                const messageElement = document.createElement('div');
                messageElement.id = `${field}_validation_message`;
                messageElement.className = 'text-red-500 text-xs mt-1';
                messageElement.textContent = message;

                input.parentNode.appendChild(messageElement);
            }

            function getFieldLabel(field) {
                const labels = {
                    'cpf': 'CPF',
                    'rg': 'RG',
                    'matricula': 'Matrícula'
                };
                return labels[field] || field;
            }

            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function(e) {
                    let value = e.target.value;

                    value = value.replace(/\D/g, '');

                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }

                    if (value.length > 0) {
                        if (value.length <= 3) {} else if (value.length <= 6) {
                            value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
                        } else if (value.length <= 9) {
                            value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                        } else {
                            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                        }
                    }

                    e.target.value = value;
                });

                cpfInput.addEventListener('paste', function(e) {
                    setTimeout(function() {
                        const event = new Event('input');
                        cpfInput.dispatchEvent(event);
                    }, 0);
                });
            }

            const rgInput = document.getElementById('rg');

            if (rgInput) {
                rgInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, ''); 

                    if (value.length > 9) {
                        value = value.substring(0, 9); 
                    }

                    if (value.length > 2) {
                        value = value.slice(0, 2) + '.' + value.slice(2);
                    }
                    if (value.length > 6) {
                        value = value.slice(0, 6) + '.' + value.slice(6);
                    }
                    if (value.length > 10) {
                        value = value.slice(0, 10) + '-' + value.slice(10);
                    } else if (value.length > 9) {
                        value = value.slice(0, 9) + '-' + value.slice(9);
                    }

                    e.target.value = value;
                });
            }

            const addSecondMatriculaCheckbox = document.getElementById('add_second_matricula');
            const addSecondMatriculaContainer = document.getElementById('add_second_matricula_container');
            const removeSecondMatriculaCheckbox = document.getElementById('remove_second_matricula');
            
            if (addSecondMatriculaCheckbox && addSecondMatriculaContainer) {
                addSecondMatriculaCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        addSecondMatriculaContainer.classList.remove('hidden');
                    } else {
                        addSecondMatriculaContainer.classList.add('hidden');
                        document.getElementById('second_matricula').value = '';
                    }
                });
            }
            
            if (removeSecondMatriculaCheckbox) {
                removeSecondMatriculaCheckbox.addEventListener('change', function() {
                    const secondMatriculaInput = document.getElementById('second_matricula');
                    if (this.checked) {
                        secondMatriculaInput.setAttribute('disabled', 'disabled');
                        secondMatriculaInput.classList.add('bg-gray-100');
                    } else {
                        secondMatriculaInput.removeAttribute('disabled');
                        secondMatriculaInput.classList.remove('bg-gray-100');
                    }
                });
            }
        });
    </script>
</x-app-layout>