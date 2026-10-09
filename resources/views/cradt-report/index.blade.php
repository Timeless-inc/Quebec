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

                    <div class="dropdown" id="exportDropdown" style="display: none;">
                        <button class="inline-flex justify-center items-center gap-2 w-full sm:w-auto px-4 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-md text-sm font-semibold transition dropdown-toggle" type="button" id="exportDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export"></i>
                            Exportar
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-gray-200" aria-labelledby="exportDropdownButton">
                            <li>
                                <a class="dropdown-item export-option" href="#" data-format="xlsx">
                                    <i class="fas fa-file-excel me-2 text-green-600"></i>Excel (.xlsx)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item export-option" href="#" data-format="pdf">
                                    <i class="fas fa-file-pdf me-2 text-red-600"></i>PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item export-option" href="#" data-format="csv">
                                    <i class="fas fa-file-csv me-2 text-gray-500"></i>CSV
                                </a>
                            </li>
                        </ul>
                    </div>

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
                                            <option value="{{ $ano }}" @selected($loop->last)>{{ $ano }}</option>
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
                                            <option value="{{ $ano }}" @selected($loop->last)>{{ $ano }}</option>
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

                        <label for="stackBars" class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input id="stackBars" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            Empilhar barras
                        </label>
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
                        <div id="graficoRequerimentos" class="hidden h-full w-full p-3"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @vite('resources/js/report-charts.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('graficoRequerimentos');
            const chartEmptyState = document.getElementById('chartEmptyState');
            const exportDropdown = document.getElementById('exportDropdown');
            const generateButton = document.getElementById('generateChart');
            const stackToggle = document.getElementById('stackBars');
            const reportTypeOptions = document.querySelectorAll('.report-type-option');
            const exportUrl = "{{ route('cradt-report.export') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let chart = null;
            let currentChartData = null;

            const FILTER_LABELS = {
                tipo: 'Tipo de Requisição',
                status: 'Status',
                turno: 'Turno',
                curso: 'Curso',
                responsavel: 'Responsável',
                tempoResolucao: 'Tempo de Resolução'
            };

            const VALUE_AXIS_TITLE = 'Quantidade de Requerimentos';
            const MAX_VERTICAL_CATEGORIES = 8;
            const MAX_LABELLED_BARS = 40;
            const MAX_WRAPPED_LEGEND_ITEMS = 10;
            const CHART_CHROME_HEIGHT = 180;
            const MAX_EXPORT_IMAGE_PIXELS = 2000000;
            const chartColors = ['#2563eb', '#16a34a', '#eab308', '#dc2626', '#4f46e5', '#0891b2', '#6b7280', '#db2777'];

            function periodLabel(mesSelect, ano) {
                if (mesSelect.value === 'all') {
                    return `Ano de ${ano}`;
                }

                return `${mesSelect.options[mesSelect.selectedIndex].text}/${ano}`;
            }

            function setChartVisibility(isVisible) {
                chartElement.classList.toggle('hidden', !isVisible);
                chartEmptyState.classList.toggle('hidden', isVisible);
            }

            function resetChart() {
                if (chart) {
                    chart.clear();
                }

                chartElement.parentElement.style.height = '';
                currentChartData = null;
                exportDropdown.style.display = 'none';
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

            // Com muitas categorias as barras ficam na horizontal para os rótulos caberem
            function buildAxes(categoryTitle, categories, horizontal) {
                const categoryAxis = {
                    type: 'category',
                    data: categories,
                    name: categoryTitle,
                    nameLocation: 'middle',
                    nameGap: 12,
                    nameMoveOverlap: true,
                    nameTextStyle: {
                        fontWeight: 600,
                        color: '#374151'
                    },
                    axisTick: {
                        alignWithLabel: true
                    },
                    axisLabel: {
                        interval: 0,
                        width: horizontal ? 230 : 120,
                        overflow: horizontal ? 'truncate' : 'break',
                        color: '#4b5563'
                    }
                };
                const valueAxis = {
                    type: 'value',
                    name: VALUE_AXIS_TITLE,
                    nameLocation: 'middle',
                    nameGap: 12,
                    nameMoveOverlap: true,
                    nameTextStyle: {
                        fontWeight: 600,
                        color: '#374151'
                    },
                    minInterval: 1,
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(148, 163, 184, 0.25)'
                        }
                    }
                };

                return horizontal ? {
                    xAxis: valueAxis,
                    yAxis: Object.assign(categoryAxis, {
                        inverse: true
                    })
                } : {
                    xAxis: categoryAxis,
                    yAxis: valueAxis
                };
            }

            function baseOption(title, subtitle, hasLegend) {
                return {
                    color: chartColors,
                    title: {
                        text: title,
                        subtext: subtitle,
                        left: 'center',
                        top: 4,
                        textStyle: {
                            fontSize: 16,
                            fontWeight: 600,
                            color: '#1f2937'
                        },
                        subtextStyle: {
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    grid: {
                        left: 28,
                        right: 48,
                        top: hasLegend ? 124 : 72,
                        bottom: 24,
                        outerBoundsMode: 'same',
                        outerBoundsContain: 'all'
                    }
                };
            }

            // bandHeight: altura reservada por categoria quando as barras ficam na horizontal
            function renderChart(option, categoryCount, bandHeight) {
                const wrapper = chartElement.parentElement;

                setChartVisibility(true);
                wrapper.style.height = '';

                if (bandHeight) {
                    const neededHeight = categoryCount * bandHeight + CHART_CHROME_HEIGHT;

                    if (neededHeight > wrapper.clientHeight) {
                        wrapper.style.height = `${neededHeight}px`;
                    }
                }

                if (!chart) {
                    chart = echarts.init(chartElement);
                }

                chart.resize();
                chart.setOption(option, true);
                exportDropdown.style.display = '';
            }

            function renderSimpleChart() {
                const { filtro, labels, data, title } = currentChartData;
                const horizontal = labels.length > MAX_VERTICAL_CATEGORIES;
                const total = data.reduce((sum, value) => sum + value, 0);
                const share = value => `${value} (${(value / total * 100).toFixed(1).replace('.', ',')}%)`;
                const option = baseOption(title, `Total: ${total} requerimentos`, false);

                option.tooltip.valueFormatter = share;

                renderChart(Object.assign(option, buildAxes(FILTER_LABELS[filtro], labels, horizontal), {
                    series: [{
                        type: 'bar',
                        name: 'Requerimentos',
                        data: data,
                        colorBy: 'data',
                        barMaxWidth: 64,
                        itemStyle: {
                            borderRadius: horizontal ? [0, 6, 6, 0] : [6, 6, 0, 0]
                        },
                        label: {
                            show: true,
                            position: horizontal ? 'right' : 'top',
                            formatter: params => share(params.value)
                        }
                    }]
                }), labels.length, horizontal ? 20 : 0);
            }

            function renderCrossChart() {
                const { filtro1, filtro2, rows, columns, data, title } = currentChartData;
                const horizontal = columns.length > MAX_VERTICAL_CATEGORIES;
                const stacked = stackToggle.checked;
                const showLabels = rows.length * columns.length <= MAX_LABELLED_BARS;
                const total = data.reduce((sum, row) => sum + row.reduce((rowSum, value) => rowSum + value, 0), 0);
                const option = baseOption(title, `Cores: ${FILTER_LABELS[filtro1]} · Total: ${total} requerimentos`, true);

                option.legend = {
                    type: rows.length > MAX_WRAPPED_LEGEND_ITEMS ? 'scroll' : 'plain',
                    top: 62,
                    left: 'center',
                    width: '90%'
                };

                renderChart(Object.assign(option, buildAxes(FILTER_LABELS[filtro2], columns, horizontal), {
                    series: rows.map((row, index) => ({
                        type: 'bar',
                        name: row,
                        data: data[index],
                        stack: stacked ? 'total' : undefined,
                        barMaxWidth: 48,
                        emphasis: {
                            focus: 'series'
                        },
                        label: {
                            show: showLabels,
                            position: stacked ? 'inside' : (horizontal ? 'right' : 'top'),
                            formatter: params => params.value > 0 ? params.value : ''
                        },
                        labelLayout: {
                            hideOverlap: true
                        }
                    }))
                }), columns.length, horizontal ? (stacked ? 24 : Math.max(24, rows.length * 10 + 8)) : 0);
            }

            function fetchReport(url) {
                generateButton.disabled = true;

                return fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .finally(() => {
                        generateButton.disabled = false;
                    });
            }

            function showNoData(message) {
                resetChart();
                alert(message);
            }

            generateButton.addEventListener('click', function() {
                const reportType = document.querySelector('input[name="reportType"]:checked').value;

                if (reportType === 'simple') {
                    generateSimpleChart();
                } else {
                    generateCrossReport();
                }
            });

            function generateSimpleChart() {
                const filtro = document.getElementById('filtro').value;
                const mesSelect = document.getElementById('mes');
                const mes = mesSelect.value;
                const ano = document.getElementById('ano').value;

                fetchReport(`/getFilteredData?filtro=${filtro}&mes=${mes}&ano=${ano}`)
                    .then(data => {
                        if (!Array.isArray(data) || data.length === 0) {
                            showNoData('Não há dados para o período e filtro selecionados.');
                            return;
                        }

                        currentChartData = {
                            type: 'simple',
                            params: {
                                tipoRelatorio: 'simple',
                                filtro: filtro,
                                mes: mes,
                                ano: ano
                            },
                            filtro: filtro,
                            labels: data.map(item => item.label),
                            data: data.map(item => Number(item.total)),
                            title: `Total de Requerimentos por ${FILTER_LABELS[filtro]} - ${periodLabel(mesSelect, ano)}`
                        };

                        renderSimpleChart();
                    })
                    .catch(() => showNoData('Não foi possível gerar o gráfico. Tente novamente.'));
            }

            function generateCrossReport() {
                const filtro1 = document.getElementById('filtro1').value;
                const filtro2 = document.getElementById('filtro2').value;
                const mesSelect = document.getElementById('mesCross');
                const mes = mesSelect.value;
                const ano = document.getElementById('anoCross').value;

                if (filtro1 === filtro2) {
                    showNoData('Os dois filtros não podem ser iguais. Por favor, selecione filtros diferentes.');
                    return;
                }

                fetchReport(`/getCrossReport?filtro1=${filtro1}&filtro2=${filtro2}&mes=${mes}&ano=${ano}`)
                    .then(data => {
                        if (data.error) {
                            showNoData(data.error);
                            return;
                        }

                        if (!data.rotulos || data.rotulos.length === 0) {
                            showNoData('Não há dados suficientes para gerar este relatório.');
                            return;
                        }

                        // Filtro 1 são as séries (cores) e Filtro 2 o eixo das categorias
                        currentChartData = {
                            type: 'cross',
                            params: {
                                tipoRelatorio: 'cross',
                                filtro1: filtro1,
                                filtro2: filtro2,
                                mes: mes,
                                ano: ano
                            },
                            filtro1: filtro1,
                            filtro2: filtro2,
                            rows: data.categorias,
                            columns: data.rotulos,
                            data: data.dados.map(row => row.map(Number)),
                            title: `${data.titulo} - ${periodLabel(mesSelect, ano)}`
                        };

                        renderCrossChart();
                    })
                    .catch(() => showNoData('Não foi possível gerar o gráfico. Tente novamente.'));
            }

            stackToggle.addEventListener('change', function() {
                if (currentChartData && currentChartData.type === 'cross') {
                    renderCrossChart();
                }
            });

            window.addEventListener('resize', function() {
                if (chart && currentChartData) {
                    chart.resize();
                }
            });

            function fileName(title, extension) {
                return `${title.replace(/[\\/:*?"<>|]/g, '').replace(/\s+/g, '_')}.${extension}`;
            }

            function downloadBlob(blob, name) {
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', name);
                link.style.visibility = 'hidden';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            function csvCell(value) {
                const text = String(value);

                return /[",\n]/.test(text) ? `"${text.replace(/"/g, '""')}"` : text;
            }

            function exportCSV() {
                let rows;

                if (currentChartData.type === 'simple') {
                    rows = [['Categoria', 'Total']].concat(
                        currentChartData.labels.map((label, index) => [label, currentChartData.data[index]])
                    );
                } else {
                    rows = [[''].concat(currentChartData.columns)].concat(
                        currentChartData.rows.map((row, index) => [row].concat(currentChartData.data[index]))
                    );
                }

                const csvContent = rows.map(row => row.map(csvCell).join(',')).join('\n');

                // BOM para o Excel reconhecer os acentos
                downloadBlob(new Blob(['﻿' + csvContent], {
                    type: 'text/csv;charset=utf-8;'
                }), fileName(currentChartData.title, 'csv'));
            }

            function exportFile(format) {
                const body = new URLSearchParams(currentChartData.params);
                const title = currentChartData.title;

                body.set('formato', format);

                if (format === 'pdf') {
                    // Limita a resolução para o servidor conseguir montar o PDF de gráficos muito altos
                    const pixelRatio = Math.min(2, Math.sqrt(MAX_EXPORT_IMAGE_PIXELS / (chart.getWidth() * chart.getHeight())));

                    body.set('grafico', chart.getDataURL({
                        type: 'png',
                        pixelRatio: pixelRatio,
                        backgroundColor: '#ffffff'
                    }));
                }

                fetch(exportUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: body
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Falha na exportação: ${response.status}`);
                        }

                        return response.blob();
                    })
                    .then(blob => downloadBlob(blob, fileName(title, format)))
                    .catch(() => alert('Não foi possível exportar o relatório. Tente novamente.'));
            }

            document.querySelectorAll('.export-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (!currentChartData) {
                        return;
                    }

                    const format = this.getAttribute('data-format');

                    if (format === 'csv') {
                        exportCSV();
                    } else {
                        exportFile(format);
                    }
                });
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
                const currentSelection = filtro2Select.value;

                filtro2Select.innerHTML = '';

                Object.entries(FILTER_LABELS).forEach(([value, label]) => {
                    if (value !== filtro1Value) {
                        const optElement = document.createElement('option');
                        optElement.value = value;
                        optElement.textContent = label;
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
