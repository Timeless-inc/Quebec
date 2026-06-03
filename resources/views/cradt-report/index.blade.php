<x-app-cradt>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-chart-bar mr-2 text-black-600"></i>Relatórios
            </h2>
            <p class="text-sm text-gray-500 mb-0">Acompanhe os indicadores e gere relatórios do atendimento.</p>
        </div>
    </x-slot>

    @php
        $totalRequerimentos = $requerimentos->sum('total');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-5 mb-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                        Central de relatórios
                    </span>
                    <h3 class="text-lg font-semibold text-gray-800 mt-2 mb-1">Opções de Relatório</h3>
                    <p class="text-sm text-gray-500 mb-0">Selecione filtros, gere gráficos e exporte os dados quando precisar.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:items-center action-buttons">
                    <div class="dropdown">
                        <button class="inline-flex justify-center items-center gap-2 w-full sm:w-auto px-4 py-2.5 border border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md text-sm font-semibold transition dropdown-toggle" type="button" id="userReportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-cog"></i>
                            Meu Relatório
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-gray-200" aria-labelledby="userReportDropdown">
                            <li>
                                <a class="dropdown-item user-report-option" href="#" data-period="mes-atual">
                                    <i class="fas fa-calendar-day me-2 text-blue-500"></i>Mês Atual
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item user-report-option" href="#" data-period="mes-anterior">
                                    <i class="fas fa-calendar-week me-2 text-blue-500"></i>Mês Anterior
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item user-report-option" href="#" data-period="ano-atual">
                                    <i class="fas fa-calendar me-2 text-blue-500"></i>Ano Atual
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item user-report-option" href="#" data-period="personalizado">
                                    <i class="fas fa-calendar-alt me-2 text-blue-500"></i>Período Personalizado
                                </a>
                            </li>
                        </ul>
                    </div>

                    <button id="exportCSV" class="inline-flex justify-center items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-md text-sm font-semibold transition" style="display: none;">
                        <i class="fas fa-file-csv"></i>
                        Exportar CSV
                    </button>

                    <button id="generateChart" class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow-sm transition">
                        <i class="fas fa-chart-line"></i>
                        Gerar Gráfico
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="customPeriodModal" tabindex="-1" aria-labelledby="customPeriodModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-lg border-0 shadow-lg">
                    <div class="modal-header border-gray-100">
                        <h5 class="modal-title font-semibold text-gray-800" id="customPeriodModalLabel">Selecione o Período</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="customPeriodForm">
                            <div class="mb-3">
                                <label for="startDate" class="form-label text-sm text-gray-600">Data Inicial</label>
                                <input type="date" class="form-control rounded-md" id="startDate" name="startDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label text-sm text-gray-600">Data Final</label>
                                <input type="date" class="form-control rounded-md" id="endDate" name="endDate" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-gray-50 border-gray-100">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="generateCustomPeriodReport">Gerar Relatório</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[18rem_minmax(0,1fr)] gap-5 items-start">
            <aside class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-gray-800 m-0">Resumo por Situação</h5>
                            <p class="text-xs text-gray-500 mb-0 mt-0.5">Total agrupado por situação</p>
                        </div>
                        <span class="inline-flex items-center justify-center min-w-10 h-8 px-2 rounded-md bg-gray-100 text-gray-700 text-sm font-semibold">
                            {{ $totalRequerimentos }}
                        </span>
                    </div>
                    <div class="p-2">
                        <ul class="divide-y divide-gray-100 mb-0">
                            @forelse($requerimentos as $requerimento)
                                <li class="flex justify-between items-center gap-3 px-2 py-2">
                                    <span class="text-sm text-gray-600 truncate">{{ $requerimento['situacao'] }}</span>
                                    <span class="inline-flex items-center justify-center min-w-9 px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">{{ $requerimento['total'] }}</span>
                                </li>
                            @empty
                                <li class="text-center text-sm text-gray-500 py-4">Nenhum dado disponível</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h5 class="font-semibold text-gray-800 m-0">Tipo de Relatório</h5>
                        <p class="text-xs text-gray-500 mb-0 mt-0.5">Escolha a leitura do gráfico</p>
                    </div>
                    <div class="p-3 space-y-2">
                        <label for="reportType_simple" class="report-type-option flex items-center gap-3 rounded-md border border-blue-200 bg-blue-50 p-3 cursor-pointer transition">
                            <input id="reportType_simple" type="radio" name="reportType" value="simple" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span>
                                <span class="block text-sm font-semibold text-gray-800">Simples</span>
                                <span class="block text-xs text-gray-500">Uma dimensão por vez</span>
                            </span>
                        </label>

                        <label for="reportType_cross" class="report-type-option flex items-center gap-3 rounded-md border border-gray-200 bg-white p-3 cursor-pointer transition hover:bg-gray-50">
                            <input id="reportType_cross" type="radio" name="reportType" value="cross" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span>
                                <span class="block text-sm font-semibold text-gray-800">Cruzado</span>
                                <span class="block text-xs text-gray-500">Compare duas dimensões</span>
                            </span>
                        </label>
                    </div>
                </div>
            </aside>

            <section class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden min-h-[620px]">
                <div class="px-4 sm:px-5 py-4 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                        <div>
                            <h5 class="font-semibold text-gray-800 m-0">Filtros</h5>
                            <p class="text-sm text-gray-500 mb-0">Defina o recorte antes de gerar o gráfico.</p>
                        </div>
                        <span class="inline-flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-filter text-blue-500"></i>
                            Atualizado conforme seleção
                        </span>
                    </div>

                    <div id="simpleFilters" class="flex flex-col space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label for="filtro" class="text-sm font-medium text-gray-600 block mb-1">Filtrar por</label>
                                <select id="filtro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="status">Status</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>
                            </div>

                            <div>
                                <label for="mes" class="text-sm font-medium text-gray-600 block mb-1">Mês</label>
                                <select id="mes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
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
                                <label for="ano" class="text-sm font-medium text-gray-600 block mb-1">Ano</label>
                                <select id="ano" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
                                    @if(isset($anosDisponiveis))
                                        @foreach($anosDisponiveis as $ano)
                                            <option value="{{ $ano }}">{{ $ano }}</option>
                                        @endforeach
                                    @else
                                        @php
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

                    <div id="crossFilters" class="flex flex-col space-y-3" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label for="filtro1" class="text-sm font-medium text-gray-600 block mb-1">Filtro 1</label>
                                <select id="filtro1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="status">Status</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>
                            </div>

                            <div>
                                <label for="filtro2" class="text-sm font-medium text-gray-600 block mb-1">Filtro 2</label>
                                <select id="filtro2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
                                    <option value="status">Status</option>
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label for="mesCross" class="text-sm font-medium text-gray-600 block mb-1">Mês</label>
                                <select id="mesCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
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
                                <label for="anoCross" class="text-sm font-medium text-gray-600 block mb-1">Ano</label>
                                <select id="anoCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full">
                                    @if(isset($anosDisponiveis))
                                        @foreach($anosDisponiveis as $ano)
                                            <option value="{{ $ano }}">{{ $ano }}</option>
                                        @endforeach
                                    @else
                                        @php
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

                <div class="p-4 sm:p-5">
                    <div class="relative h-[420px] md:h-[560px] lg:h-[620px] rounded-lg border border-dashed border-gray-200 bg-gray-50/70">
                        <div id="chartEmptyState" class="absolute inset-0 flex items-center justify-center p-6 text-center">
                            <div class="max-w-sm">
                                <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-xl"></i>
                                </div>
                                <h4 class="text-base font-semibold text-gray-800 mb-1">Pronto para gerar o gráfico</h4>
                                <p class="text-sm text-gray-500 mb-0">Ajuste os filtros acima e clique em Gerar Gráfico para visualizar os dados.</p>
                            </div>
                        </div>
                        <canvas id="graficoRequerimentos" class="hidden h-full w-full p-3"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('graficoRequerimentos');
            const chartEmptyState = document.getElementById('chartEmptyState');
            const exportButton = document.getElementById('exportCSV');
            const reportTypeOptions = document.querySelectorAll('.report-type-option');
            let chart;
            let currentChartData = null;

            const chartColors = [
                { background: 'rgba(37, 99, 235, 0.65)', border: 'rgba(37, 99, 235, 1)' },
                { background: 'rgba(22, 163, 74, 0.65)', border: 'rgba(22, 163, 74, 1)' },
                { background: 'rgba(234, 179, 8, 0.65)', border: 'rgba(234, 179, 8, 1)' },
                { background: 'rgba(220, 38, 38, 0.65)', border: 'rgba(220, 38, 38, 1)' },
                { background: 'rgba(79, 70, 229, 0.65)', border: 'rgba(79, 70, 229, 1)' },
                { background: 'rgba(8, 145, 178, 0.65)', border: 'rgba(8, 145, 178, 1)' },
                { background: 'rgba(107, 114, 128, 0.65)', border: 'rgba(107, 114, 128, 1)' },
                { background: 'rgba(219, 39, 119, 0.65)', border: 'rgba(219, 39, 119, 1)' }
            ];

            function setChartVisibility(isVisible) {
                chartCanvas.classList.toggle('hidden', !isVisible);
                chartCanvas.style.display = isVisible ? 'block' : 'none';
                chartEmptyState.classList.toggle('hidden', isVisible);
            }

            function resetChart() {
                if (chart) {
                    chart.destroy();
                    chart = null;
                }

                currentChartData = null;
                exportButton.style.display = 'none';
                setChartVisibility(false);
            }

            function updateReportTypeCards() {
                reportTypeOptions.forEach(option => {
                    const input = option.querySelector('input[type="radio"]');
                    option.classList.toggle('border-blue-200', input.checked);
                    option.classList.toggle('bg-blue-50', input.checked);
                    option.classList.toggle('border-gray-200', !input.checked);
                    option.classList.toggle('bg-white', !input.checked);
                });
            }

            document.querySelectorAll('input[name="reportType"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const isSimple = this.value === 'simple';
                    document.getElementById('simpleFilters').style.display = isSimple ? 'flex' : 'none';
                    document.getElementById('crossFilters').style.display = isSimple ? 'none' : 'flex';
                    updateReportTypeCards();
                    resetChart();
                });
            });

            function exportToCSV(labels, data, title) {
                const csvRows = [['Categoria', 'Total']];

                for (let i = 0; i < labels.length; i++) {
                    csvRows.push([labels[i], data[i]]);
                }

                downloadCSV(csvRows, title);
            }

            function exportCrossReportToCSV(rows, columns, data, title) {
                const csvRows = [[''].concat(columns)];

                for (let i = 0; i < rows.length; i++) {
                    csvRows.push([rows[i]].concat(data[i]));
                }

                downloadCSV(csvRows, title);
            }

            function downloadCSV(rows, title) {
                const csvContent = rows.map(row => row.join(',')).join('\n');
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
                setChartVisibility(true);
                const ctx = chartCanvas.getContext('2d');
                const reportType = document.querySelector('input[name="reportType"]:checked').value;

                if (reportType === 'simple') {
                    generateSimpleChart(ctx);
                } else {
                    generateCrossReport(ctx);
                }
            });

            function generateSimpleChart(ctx) {
                const selectedValue = document.getElementById('filtro').value;
                const selectedMes = document.getElementById('mes').value;
                const selectedAno = document.getElementById('ano').value;
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
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: title,
                                    data: dataValues,
                                    backgroundColor: chartColors.map(color => color.background),
                                    borderColor: chartColors.map(color => color.border),
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    barPercentage: 0.78,
                                    categoryPercentage: 0.55
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(148, 163, 184, 0.2)'
                                        }
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
                                            size: 16,
                                            weight: '600'
                                        },
                                        color: '#1f2937'
                                    }
                                }
                            }
                        });

                        exportButton.style.display = 'inline-flex';
                    });
            }

            function generateCrossReport(ctx) {
                const filtro1 = document.getElementById('filtro1').value;
                const filtro2 = document.getElementById('filtro2').value;
                const mes = document.getElementById('mesCross').value;
                const ano = document.getElementById('anoCross').value;

                if (filtro1 === filtro2) {
                    alert('Os dois filtros não podem ser iguais. Por favor, selecione filtros diferentes.');
                    setChartVisibility(false);
                    return;
                }

                const url = `/getCrossReport?filtro1=${filtro1}&filtro2=${filtro2}&mes=${mes}&ano=${ano}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.rotulos || data.rotulos.length === 0) {
                            alert('Não há dados suficientes para gerar este relatório.');
                            setChartVisibility(false);
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

                        const datasets = data.categorias.map((categoria, index) => ({
                            label: categoria,
                            data: data.dados[index],
                            backgroundColor: chartColors[index % chartColors.length].background,
                            borderColor: chartColors[index % chartColors.length].border,
                            borderWidth: 1,
                            borderRadius: 6
                        }));

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
                                        grid: {
                                            display: false
                                        },
                                        title: {
                                            display: true,
                                            text: filtro2.charAt(0).toUpperCase() + filtro2.slice(1)
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(148, 163, 184, 0.2)'
                                        },
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
                                            size: 16,
                                            weight: '600'
                                        },
                                        color: '#1f2937'
                                    }
                                }
                            }
                        });

                        exportButton.style.display = 'inline-flex';
                    });
            }

            exportButton.addEventListener('click', function() {
                if (!currentChartData) {
                    return;
                }

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
            });

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

            document.getElementById('filtro1').addEventListener('change', updateSecondFilter);

            function updateSecondFilter() {
                const filtro1Value = document.getElementById('filtro1').value;
                const filtro2Select = document.getElementById('filtro2');
                const allOptions = [
                    { value: 'tipo', label: 'Tipo de Requisição' },
                    { value: 'status', label: 'Status' },
                    { value: 'turno', label: 'Turno' },
                    { value: 'curso', label: 'Curso' },
                    { value: 'responsavel', label: 'Responsável' },
                    { value: 'tempoResolucao', label: 'Tempo de Resolução' }
                ];
                const currentSelection = filtro2Select.value;

                filtro2Select.innerHTML = '';

                allOptions.forEach(option => {
                    if (option.value !== filtro1Value) {
                        const optElement = document.createElement('option');
                        optElement.value = option.value;
                        optElement.textContent = option.label;
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
            updateReportTypeCards();
            setChartVisibility(false);
        });
    </script>
</x-app-cradt>
