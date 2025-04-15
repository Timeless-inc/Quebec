<title>SRE</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatórios
        </h2>
    </x-slot>

    <div class="container mx-auto mt-4 px-4">
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
                    <div class="border-b p-2">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                            <!-- Container para filtros simples -->
                            <div id="simpleFilters" class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 w-full">
                                <label for="filtro" class="text-sm text-gray-600 mr-2 mb-0">Filtrar por:</label>
                                <select id="filtro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-48">
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="status">Status</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>
                                <label for="mes" class="text-sm text-gray-600 mr-2 mb-0">Mês:</label>
                                <select id="mes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-32">
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
                                <label for="ano" class="text-sm text-gray-600 mr-2 mb-0">Ano:</label>
                                <select id="ano" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-32">
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

                            <!-- Container para filtros cruzados (inicialmente escondido) -->
                            <div id="crossFilters" class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 w-full" style="display: none;">
                                <label for="filtro1" class="text-sm text-gray-600 mr-2 mb-0">Filtro 1:</label>
                                <select id="filtro1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-40">
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="status">Status</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>

                                <label for="filtro2" class="text-sm text-gray-600 mr-2 mb-0">Filtro 2:</label>
                                <select id="filtro2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-40">
                                    <option value="status">Status</option>
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                    <option value="responsavel">Responsável</option>
                                    <option value="tempoResolucao">Tempo de Resolução</option>
                                </select>

                                <label for="mesCross" class="text-sm text-gray-600 mr-2 mb-0">Mês:</label>
                                <select id="mesCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-32">
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

                                <label for="anoCross" class="text-sm text-gray-600 mr-2 mb-0">Ano:</label>
                                <select id="anoCross" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-3 p-2 w-full md:w-32">
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

                            <div class="flex space-x-2 w-full md:w-auto">
                                <button id="generateChart" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-300">Gerar Gráfico</button>
                                <button id="exportCSV" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300" style="display: none;">
                                    <i class="fas fa-download"></i> Exportar CSV
                                </button>
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
    </script>
</x-appcradt>