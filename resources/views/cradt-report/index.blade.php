<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-chart-bar mr-2"></i>Relatórios
        </h2>
    </x-slot>

    <div class="container mx-auto mt-4 px-4">
        <!-- Barra de ações no topo -->
        <div class="flex flex-col sm:flex-row justify-between gap-3 mb-6 bg-white rounded-lg shadow-sm p-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-700">Opções de Relatório</h3>

            </div>

            <div class="flex flex-wrap gap-2 action-buttons">
                <div class="dropdown">
                    <button class="btn btn-outline-primary action-button dropdown-toggle" type="button" id="userReportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-cog me-1"></i>Meu Relatório
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userReportDropdown">
                        <li>
                            <a class="dropdown-item user-report-option" href="#" data-period="mes-atual">
                                <i class="fas fa-calendar-day me-1"></i>Mês Atual
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item user-report-option" href="#" data-period="mes-anterior">
                                <i class="fas fa-calendar-week me-1"></i>Mês Anterior
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item user-report-option" href="#" data-period="ano-atual">
                                <i class="fas fa-calendar me-1"></i>Ano Atual
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item user-report-option" href="#" data-period="personalizado">
                                <i class="fas fa-calendar-alt me-1"></i>Período Personalizado
                            </a>
                        </li>
                    </ul>
                </div>
                <button id="exportCSV" class="btn btn-outline-secondary action-button" style="display: none;">
                    <i class="fas fa-file-csv me-1"></i>Exportar CSV
                </button>
                <button id="generateChart" class="btn btn-primary action-button">
                    <i class="fas fa-chart-line me-1"></i>Gerar Gráfico
                </button>
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

        <div class="flex flex-col md:flex-row">
            <!-- Container for Resumo Card -->
            <div class="flex flex-col w-full md:w-72 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="border-b p-2">
                        <h5 class="font-medium text-gray-700 m-0">Resumo por Situação</h5>
                    </div>
                    <div class="p-2">
                        <ul class="divide-y divide-gray-200">
                            @forelse($requerimentos as $requerimento)
                            <li class="flex justify-between items-center py-1">
                                <span class="text-gray-500">{{ $requerimento['situacao'] }}</span>
                                <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $requerimento['total'] }}</span>
                            </li>
                            @empty
                            <li class="text-center py-1">Nenhum dado disponível</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Tipo de Relatório -->
                <div class="bg-white rounded-lg shadow-sm mt-4">
                    <div class="border-b p-2">
                        <h5 class="font-medium text-gray-700 m-0">Tipo de Relatório</h5>
                    </div>
                    <div class="p-2">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center">
                                <input id="reportType_simple" type="radio" name="reportType" value="simple" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="reportType_simple" class="ml-2 text-sm font-medium text-gray-700">Simples</label>
                            </div>
                            <div class="flex items-center">
                                <input id="reportType_cross" type="radio" name="reportType" value="cross" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="reportType_cross" class="ml-2 text-sm font-medium text-gray-700">Cruzado</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container for Graph Card -->
            <div class="flex flex-grow mt-4 md:mt-0 md:ml-3">
                <div class="bg-white rounded-lg shadow-sm w-full flex flex-col">
                    <div class="border-b p-3">
                        <h5 class="font-medium text-gray-700 m-0 mb-3">Filtros</h5>

                        <!-- Container para filtros simples -->
                        <div id="simpleFilters" class="flex flex-col space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <div>
                                    <label for="filtro" class="text-sm text-gray-600 block mb-1">Filtrar por:</label>
                                    <select id="filtro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        <option value="tipo">Tipo de Requisição</option>
                                        <option value="status">Status</option>
                                        <option value="turno">Turno</option>
                                        <option value="curso">Curso</option>
                                        <option value="responsavel">Responsável</option>
                                        <option value="tempoResolucao">Tempo de Resolução</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="mes" class="text-sm text-gray-600 block mb-1">Mês:</label>
                                    <select id="mes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        <option value="all">Anual</option>
                                        <option value="01">Janeiro</option>
                                        <option value="02">Fevereiro</option>
                                        <option value="03">Março</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Maio</option>
                                        <option value="06">Junho</option>
                                        <option value="07">Julho</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="ano" class="text-sm text-gray-600 block mb-1">Ano:</label>
                                    <select id="ano" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        @if(isset($anosDisponiveis))
                                        @foreach($anosDisponiveis as $ano)
                                        <option value="{{ $ano }}">{{ $ano }}</option>
                                        @endforeach
                                        @else
                                        @php
                                        // Fallback caso a variável $anosDisponiveis não esteja disponível
                                        $anoAtual = date('Y');
                                        $anoInicial = 2025;
                                        @endphp
                                        @for ($i = $anoInicial; $i <= $anoAtual; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Container para filtros cruzados (inicialmente escondido) -->
                        <div id="crossFilters" class="flex flex-col space-y-3" style="display: none;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="filtro1" class="text-sm text-gray-600 block mb-1">Filtro 1:</label>
                                    <select id="filtro1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        <option value="tipo">Tipo de Requisição</option>
                                        <option value="status">Status</option>
                                        <option value="turno">Turno</option>
                                        <option value="curso">Curso</option>
                                        <option value="responsavel">Responsável</option>
                                        <option value="tempoResolucao">Tempo de Resolução</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="filtro2" class="text-sm text-gray-600 block mb-1">Filtro 2:</label>
                                    <select id="filtro2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        <option value="status">Status</option>
                                        <option value="tipo">Tipo de Requisição</option>
                                        <option value="turno">Turno</option>
                                        <option value="curso">Curso</option>
                                        <option value="responsavel">Responsável</option>
                                        <option value="tempoResolucao">Tempo de Resolução</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="mesCross" class="text-sm text-gray-600 block mb-1">Mês:</label>
                                    <select id="mesCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        <option value="all">Anual</option>
                                        <option value="01">Janeiro</option>
                                        <option value="02">Fevereiro</option>
                                        <option value="03">Março</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Maio</option>
                                        <option value="06">Junho</option>
                                        <option value="07">Julho</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="anoCross" class="text-sm text-gray-600 block mb-1">Ano:</label>
                                    <select id="anoCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                                        @if(isset($anosDisponiveis))
                                        @foreach($anosDisponiveis as $ano)
                                        <option value="{{ $ano }}">{{ $ano }}</option>
                                        @endforeach
                                        @else
                                        @php
                                        // Fallback caso a variável $anosDisponiveis não esteja disponível
                                        $anoAtual = date('Y');
                                        $anoInicial = 2025;
                                        @endphp
                                        @for ($i = $anoInicial; $i <= $anoAtual; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 flex-grow h-96 md:h-[600px] lg:h-[700px]">
                        <canvas id="graficoRequerimentos" style="display: none; height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labelsTipo = JSON.parse('{!! $requerimentosTipo->pluck("tipoRequisicao") !!}');
            const dataTipo = JSON.parse('{!! $requerimentosTipo->pluck("total") !!}');
            const labelsStatus = JSON.parse('{!! $requerimentosStatus->pluck("status") !!}');
            const dataStatus = JSON.parse('{!! $requerimentosStatus->pluck("total") !!}');
            const labelsTurno = JSON.parse('{!! $requerimentosTurnos->pluck("turno") !!}');
            const dataTurno = JSON.parse('{!! $requerimentosTurnos->pluck("total") !!}');
            const labelsCurso = JSON.parse('{!! $requerimentosCursos->pluck("curso") !!}');
            const dataCurso = JSON.parse('{!! $requerimentosCursos->pluck("total") !!}');

            let chart;
            let currentChartData = null;

            // Manipula alteração do tipo de relatório
            document.querySelectorAll('input[name="reportType"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const isSimple = this.value === 'simple';
                    document.getElementById('simpleFilters').style.display = isSimple ? 'flex' : 'none';
                    document.getElementById('crossFilters').style.display = isSimple ? 'none' : 'flex';

                    if (chart) {
                        chart.destroy();
                        chart = null;
                    }

                    document.getElementById('graficoRequerimentos').style.display = 'none';
                    document.getElementById('exportCSV').style.display = 'none';
                });
            });

            // Função para exportar dados simples para CSV
            function exportToCSV(labels, data, title) {
                const csvRows = [];
                csvRows.push(['Categoria', 'Total']);

                for (let i = 0; i < labels.length; i++) {
                    csvRows.push([labels[i], data[i]]);
                }

                const csvContent = csvRows.map(row => row.join(',')).join('\n');
                const blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `${title.replace(/\s+/g, '_')}.csv`);
                link.style.visibility = 'hidden';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            function exportCrossReportToCSV(rows, columns, data, title) {
                const csvRows = [];

                const header = [''].concat(columns);
                csvRows.push(header);

                for (let i = 0; i < rows.length; i++) {
                    const rowData = [rows[i]].concat(data[i]);
                    csvRows.push(rowData);
                }

                const csvContent = csvRows.map(row => row.join(',')).join('\n');
                const blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `${title.replace(/\s+/g, '_')}.csv`);
                link.style.visibility = 'hidden';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            document.getElementById('generateChart').addEventListener('click', function() {
                const canvas = document.getElementById('graficoRequerimentos');
                canvas.style.display = 'block';
                const ctx = canvas.getContext('2d');

                const reportType = document.querySelector('input[name="reportType"]:checked').value;

                if (reportType === 'simple') {
                    generateSimpleChart(ctx);
                } else {
                    generateCrossReport(ctx);
                }
            });

            function generateSimpleChart(ctx) {
                const selectedValue = document.getElementById("filtro").value;
                const selectedMes = document.getElementById("mes").value;
                const selectedAno = document.getElementById("ano").value;

                const url = `/getFilteredData?filtro=${selectedValue}&mes=${selectedMes}&ano=${selectedAno}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.map(item => item.label);
                        const dataValues = data.map(item => item.total);
                        const timeFrame = selectedMes === 'all' ? 'Ano' : 'Mês';
                        const title = `Total de Requerimentos por ${selectedValue.charAt(0).toUpperCase() + selectedValue.slice(1)} - ${timeFrame}: ${selectedAno}`;

                        currentChartData = {
                            type: 'simple',
                            labels: labels,
                            data: dataValues,
                            title: title
                        };

                        if (chart) {
                            chart.destroy();
                        }

                        chart = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: title,
                                    data: dataValues,
                                    backgroundColor: [
                                        "rgba(255, 99, 132, 0.5)",
                                        "rgba(54, 162, 235, 0.5)",
                                        "rgba(255, 206, 86, 0.5)",
                                        "rgba(75, 192, 192, 0.5)",
                                        "rgba(153, 102, 255, 0.5)",
                                        "rgba(255, 159, 64, 0.5)",
                                        "rgba(201, 203, 207, 0.5)",
                                        "rgba(0, 128, 0, 0.5)",
                                        "rgba(128, 0, 128, 0.5)",
                                        "rgba(255, 0, 255, 0.5)",
                                        "rgba(0, 255, 255, 0.5)",
                                        "rgba(255, 69, 0, 0.5)"
                                    ],
                                    borderColor: [
                                        "rgba(255, 99, 132, 1)",
                                        "rgba(54, 162, 235, 1)",
                                        "rgba(255, 206, 86, 1)",
                                        "rgba(75, 192, 192, 1)",
                                        "rgba(153, 102, 255, 1)",
                                        "rgba(255, 159, 64, 1)",
                                        "rgba(201, 203, 207, 1)",
                                        "rgba(0, 128, 0, 1)",
                                        "rgba(128, 0, 128, 1)",
                                        "rgba(255, 0, 255, 1)",
                                        "rgba(0, 255, 255, 1)",
                                        "rgba(255, 69, 0, 1)"
                                    ],
                                    borderWidth: 1,
                                    barPercentage: 0.8,
                                    categoryPercentage: 0.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom'
                                    },
                                    title: {
                                        display: true,
                                        text: title,
                                        font: {
                                            size: 16
                                        }
                                    }
                                }
                            }
                        });

                        document.getElementById('exportCSV').style.display = 'inline-block';
                    });
            }

            function generateCrossReport(ctx) {
                const filtro1 = document.getElementById("filtro1").value;
                const filtro2 = document.getElementById("filtro2").value;
                const mes = document.getElementById("mesCross").value;
                const ano = document.getElementById("anoCross").value;

                if (filtro1 === filtro2) {
                    alert("Os dois filtros não podem ser iguais. Por favor, selecione filtros diferentes.");
                    return;
                }

                const url = `/getCrossReport?filtro1=${filtro1}&filtro2=${filtro2}&mes=${mes}&ano=${ano}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.rotulos || data.rotulos.length === 0) {
                            alert("Não há dados suficientes para gerar este relatório.");
                            return;
                        }

                        currentChartData = {
                            type: 'cross',
                            rows: data.categorias,
                            columns: data.rotulos,
                            data: data.dados,
                            title: data.titulo
                        };

                        if (chart) {
                            chart.destroy();
                        }

                        const datasets = [];
                        const backgroundColors = [
                            "rgba(255, 99, 132, 0.5)",
                            "rgba(54, 162, 235, 0.5)",
                            "rgba(255, 206, 86, 0.5)",
                            "rgba(75, 192, 192, 0.5)",
                            "rgba(153, 102, 255, 0.5)",
                            "rgba(255, 159, 64, 0.5)",
                            "rgba(201, 203, 207, 0.5)",
                            "rgba(0, 128, 0, 0.5)",
                            "rgba(128, 0, 128, 0.5)",
                            "rgba(255, 0, 255, 0.5)",
                            "rgba(0, 255, 255, 0.5)",
                            "rgba(255, 69, 0, 0.5)"
                        ];

                        const borderColors = [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 159, 64, 1)",
                            "rgba(201, 203, 207, 1)",
                            "rgba(0, 128, 0, 1)",
                            "rgba(128, 0, 128, 1)",
                            "rgba(255, 0, 255, 1)",
                            "rgba(0, 255, 255, 1)",
                            "rgba(255, 69, 0, 1)"
                        ];

                        for (let i = 0; i < data.categorias.length; i++) {
                            datasets.push({
                                label: data.categorias[i],
                                data: data.dados[i],
                                backgroundColor: backgroundColors[i % backgroundColors.length],
                                borderColor: borderColors[i % borderColors.length],
                                borderWidth: 1
                            });
                        }

                        chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.rotulos,
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: filtro2.charAt(0).toUpperCase() + filtro2.slice(1)
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Quantidade'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        title: {
                                            display: true,
                                            text: filtro1.charAt(0).toUpperCase() + filtro1.slice(1)
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: data.titulo,
                                        font: {
                                            size: 16
                                        }
                                    }
                                }
                            }
                        });

                        document.getElementById('exportCSV').style.display = 'inline-block';
                    });
            }

            // Exportar para CSV
            document.getElementById('exportCSV').addEventListener('click', function() {
                if (currentChartData) {
                    if (currentChartData.type === 'simple') {
                        exportToCSV(
                            currentChartData.labels,
                            currentChartData.data,
                            currentChartData.title
                        );
                    } else if (currentChartData.type === 'cross') {
                        exportCrossReportToCSV(
                            currentChartData.rows,
                            currentChartData.columns,
                            currentChartData.data,
                            currentChartData.title
                        );
                    }
                }
            });

            document.getElementById('downloadUserReportPdf').addEventListener('click', function() {
                window.location.href = "{{ route('cradt-report.user-pdf') }}";
            });

            if (document.querySelector('form')) {
                document.querySelector('form').addEventListener('submit', function(e) {
                    if (document.getElementById('userPersonalReport')) {
                        if (document.getElementById('userPersonalReport').checked) {
                            e.preventDefault();
                            window.location.href = "{{ route('cradt-report.user-pdf') }}";
                        }
                    }
                });
            }

            document.getElementById('filtro1').addEventListener('change', function() {
                updateSecondFilter();
            });

            function updateSecondFilter() {
                const filtro1Value = document.getElementById('filtro1').value;
                const filtro2Select = document.getElementById('filtro2');
                const allOptions = ['tipo', 'status', 'turno', 'curso', 'responsavel', 'tempoResolucao'];

                const currentSelection = filtro2Select.value;

                filtro2Select.innerHTML = '';

                allOptions.forEach(option => {
                    if (option !== filtro1Value) {
                        const optElement = document.createElement('option');
                        optElement.value = option;
                        optElement.textContent = option.charAt(0).toUpperCase() + option.slice(1).replace('_', ' ');
                        filtro2Select.appendChild(optElement);
                    }
                });

                if (currentSelection !== filtro1Value) {
                    for (let i = 0; i < filtro2Select.options.length; i++) {
                        if (filtro2Select.options[i].value === currentSelection) {
                            filtro2Select.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            updateSecondFilter();
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.user-report-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const period = this.getAttribute('data-period');

                    if (period === 'personalizado') {
                        const modal = new bootstrap.Modal(document.getElementById('customPeriodModal'));
                        modal.show();
                    } else {
                        window.location.href = `{{ route('cradt-report.user-pdf') }}?period=${period}`;
                    }
                });
            });

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

                const modal = bootstrap.Modal.getInstance(document.getElementById('customPeriodModal'));
                modal.hide();

                window.location.href = `{{ route('cradt-report.user-pdf') }}?period=personalizado&startDate=${startDate}&endDate=${endDate}`;
            });

            document.getElementById('customPeriodModal').addEventListener('show.bs.modal', function() {
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
            });

            if (document.querySelectorAll('input[name="reportType"]').length > 0) {
                document.querySelectorAll('input[name="reportType"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const isSimple = this.value === 'simple';
                        document.getElementById('simpleFilters').style.display = isSimple ? 'flex' : 'none';
                        document.getElementById('crossFilters').style.display = isSimple ? 'none' : 'flex';

                        if (typeof chart !== 'undefined' && chart) {
                            chart.destroy();
                            chart = null;
                        }

                        if (document.getElementById('graficoRequerimentos')) {
                            document.getElementById('graficoRequerimentos').style.display = 'none';
                        }
                        if (document.getElementById('exportCSV')) {
                            document.getElementById('exportCSV').style.display = 'none';
                        }
                    });
                });
            }

            if (document.getElementById('filtro1')) {
                document.getElementById('filtro1').addEventListener('change', function() {
                    updateSecondFilter();
                });

                function updateSecondFilter() {
                    const filtro1Value = document.getElementById('filtro1').value;
                    const filtro2Select = document.getElementById('filtro2');
                    const allOptions = ['tipo', 'status', 'turno', 'curso', 'responsavel', 'tempoResolucao'];

                    const currentSelection = filtro2Select.value;

                    filtro2Select.innerHTML = '';

                    allOptions.forEach(option => {
                        if (option !== filtro1Value) {
                            const optElement = document.createElement('option');
                            optElement.value = option;
                            optElement.textContent = option.charAt(0).toUpperCase() + option.slice(1).replace('_', ' ');
                            filtro2Select.appendChild(optElement);
                        }
                    });

                    if (currentSelection !== filtro1Value) {
                        for (let i = 0; i < filtro2Select.options.length; i++) {
                            if (filtro2Select.options[i].value === currentSelection) {
                                filtro2Select.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }

                updateSecondFilter();
            }
        });
    </script>
</x-appcradt>