<title>SRE - Professor - Relatórios</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<x-app-professor-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatórios - Professor') }}
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
                                    Visualize todos os requerimentos que você processou, incluindo deferidos, indeferidos e devolvidos.
                                </p>
                                <div class="dropdown">
                                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Gerar Relatório
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><a class="dropdown-item" href="{{ route('professor.reports.processed', ['period' => 'mes-atual']) }}">Mês Atual</a></li>
                                        <li><a class="dropdown-item" href="{{ route('professor.reports.processed', ['period' => 'mes-anterior']) }}">Mês Anterior</a></li>
                                        <li><a class="dropdown-item" href="{{ route('professor.reports.processed', ['period' => 'ano-atual']) }}">Ano Atual</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="openCustomPeriodModal('processed')">Período Personalizado</a></li>
                                    </ul>
                                </div>
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
                                <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors" onclick="openCustomPeriodModal('period')">
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
                                <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors" onclick="showStatistics()">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Ver Estatísticas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para período personalizado -->
    <div class="modal fade" id="customPeriodModal" tabindex="-1" aria-labelledby="customPeriodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customPeriodModalLabel">Selecione o Período</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customPeriodForm">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Data Final</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="generateCustomPeriodReport">Gerar Relatório</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para estatísticas -->
    <div class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statisticsModalLabel">Estatísticas do Professor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="statisticsContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentReportType = '';

        function openCustomPeriodModal(reportType) {
            currentReportType = reportType;
            const modal = new bootstrap.Modal(document.getElementById('customPeriodModal'));
            
            // Definir datas padrão
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            document.getElementById('startDate').value = formatDate(firstDayOfMonth);
            document.getElementById('endDate').value = formatDate(today);
            
            modal.show();
        }

        function showStatistics() {
            const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
            modal.show();
            
            // Carregar estatísticas via AJAX
            fetch('{{ route("professor.reports.statistics") }}')
                .then(response => response.json())
                .then(data => {
                    const content = `
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h2>${data.totalProcessados}</h2>
                                        <p class="mb-0">Total Processados</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h2>${data.totalEncaminhados}</h2>
                                        <p class="mb-0">Total Recebidos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h2>${data.totalDevolvidos}</h2>
                                        <p class="mb-0">Total Devolvidos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h2>${data.totalEncaminhados > 0 ? Math.round((data.totalProcessados / data.totalEncaminhados) * 100) : 0}%</h2>
                                        <p class="mb-0">Taxa de Processamento</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mt-4">Atividade nos Últimos 6 Meses</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mês</th>
                                        <th>Processados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.estatisticasMensais.map(item => `
                                        <tr>
                                            <td>${item.mes}</td>
                                            <td>${item.total}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-4">Status dos Requerimentos</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.statusStats.map(item => `
                                        <tr>
                                            <td>${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</td>
                                            <td>${item.total}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                    document.getElementById('statisticsContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Erro ao carregar estatísticas:', error);
                    document.getElementById('statisticsContent').innerHTML = 
                        '<div class="alert alert-danger">Erro ao carregar estatísticas.</div>';
                });
        }

        document.getElementById('generateCustomPeriodReport').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (!startDate || !endDate) {
                alert('Por favor, selecione as datas inicial e final.');
                return;
            }
            
            if (new Date(startDate) > new Date(endDate)) {
                alert('A data inicial deve ser anterior à data final.');
                return;
            }
            
            let url = '';
            if (currentReportType === 'processed') {
                url = `{{ route('professor.reports.processed') }}?period=personalizado&startDate=${startDate}&endDate=${endDate}`;
            } else if (currentReportType === 'period') {
                url = `{{ route('professor.reports.period') }}?startDate=${startDate}&endDate=${endDate}`;
            }
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('customPeriodModal'));
            modal.hide();
            
            window.location.href = url;
        });
    </script>
</x-app-professor-layout>
