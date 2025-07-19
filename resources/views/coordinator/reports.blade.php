<title>SRE - Coordenador - Relatórios</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<x-app-coordinator-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatórios - Coordenador') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Relatórios Disponíveis</h3>
                        <p class="text-sm text-gray-600 mt-1">Acesse os relatórios relacionados aos requerimentos processados.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card de Relatório de Requerimentos Processados -->
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-white font-semibold">Requerimentos Processados</h3>
                                        <p class="text-blue-100 text-sm">Relatório geral de atividades</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600 text-sm mb-4">
                                    Visualize todos os requerimentos que você processou, incluindo deferidoss, indeferidos e devolvidos.
                                </p>
                                <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Gerar Relatório
                                </button>
                            </div>
                        </div>

                        <!-- Card de Relatório por Período -->
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-white font-semibold">Relatório por Período</h3>
                                        <p class="text-green-100 text-sm">Filtrar por datas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600 text-sm mb-4">
                                    Gere relatórios personalizados selecionando um período específico para análise.
                                </p>
                                <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-filter mr-2"></i>
                                    Configurar Período
                                </button>
                            </div>
                        </div>

                        <!-- Card de Estatísticas -->
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-chart-pie text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-white font-semibold">Estatísticas</h3>
                                        <p class="text-purple-100 text-sm">Dados consolidados</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600 text-sm mb-4">
                                    Visualize estatísticas detalhadas sobre sua atividade de processamento de requerimentos.
                                </p>
                                <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Ver Estatísticas
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-coordinator-layout>
