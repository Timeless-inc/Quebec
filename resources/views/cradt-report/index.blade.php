<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatórios
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="d-flex">
            <!-- Container for Resumo Card -->
            <div class="d-flex flex-column" style="width: 280px; flex-shrink: 0;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white p-2">
                        <h5 class="card-title mb-0">Resumo por Situação</h5>
                    </div>
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush">
                            @forelse($requerimentos as $requerimento)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                <span class="text-muted">{{ $requerimento['situacao'] }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $requerimento['total'] }}</span>
                            </li>
                            @empty
                            <li class="list-group-item text-center p-1">Nenhum dado disponível</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Container for Graph Card -->
            <div class="d-flex flex-grow-1 ms-3">
                <div class="card shadow-sm w-100 d-flex flex-column">
                    <div class="card-header bg-white p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <label for="filtro" class="form-label me-2 mb-0">Filtrar por:</label>
                                <select id="filtro" class="form-select me-3" style="width: 200px;">
                                    <option value="tipo">Tipo de Requisição</option>
                                    <option value="status">Status</option>
                                    <option value="turno">Turno</option>
                                    <option value="curso">Curso</option>
                                </select>
                                <label for="mes" class="form-label me-2 mb-0">Mês:</label>
                                <select id="mes" class="form-select me-3" style="width: 120px;">
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
                                <label for="ano" class="form-label me-2 mb-0">Ano:</label>
                                <select id="ano" class="form-select me-3" style="width: 120px;">
                                    @for ($i = 2025; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                            <button id="generateChart" class="btn btn-primary">Gerar Gráfico</button>
                            <button id="exportCSV" class="btn btn-secondary" style="display: none;">
                                <i class="fas fa-download"></i> Exportar CSV
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-grow-1">
                        <canvas id="graficoRequerimentos" style="display: none; flex-grow: 1;"></canvas>
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

        document.getElementById('generateChart').addEventListener('click', function() {
            const canvas = document.getElementById('graficoRequerimentos');
            canvas.style.display = 'block';
            const ctx = canvas.getContext('2d');

            const selectedValue = document.getElementById("filtro").value;
            const selectedMes = document.getElementById("mes").value;
            const selectedAno = document.getElementById("ano").value;

            const url = selectedMes === 'all' ?
                `/getFilteredData?filtro=${selectedValue}&ano=${selectedAno}` :
                `/getFilteredData?filtro=${selectedValue}&mes=${selectedMes}&ano=${selectedAno}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.label);
                    const dataValues = data.map(item => item.total);
                    const timeFrame = selectedMes === 'all' ? 'Ano' : 'Mês';
                    const title = `Total de Requerimentos por ${selectedValue.charAt(0).toUpperCase() + selectedValue.slice(1)} - ${timeFrame}: ${selectedAno}`;

                    currentChartData = {
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
                                    display: false
                                }
                            }
                        }
                    });

                    document.getElementById('exportCSV').style.display = 'inline-block';
                });
        });

        document.getElementById('exportCSV').addEventListener('click', function() {
            if (currentChartData) {
                exportToCSV(
                    currentChartData.labels,
                    currentChartData.data,
                    currentChartData.title
                );
            }
        });
    </script>
</x-appcradt>