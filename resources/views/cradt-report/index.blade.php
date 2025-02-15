<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<x-appcradt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatórios
        </h2>
    </x-slot>

    <div class="container mt-4 justify-content-between align-items-center justify-content-center">
        <div class="row">
            <!-- Card do Resumo -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white p-2">
                        <h5 class="card-title mb-0">Resumo por Situação</h5>
                    </div>
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush">
                            @forelse($requerimentos as $requerimento)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                <span class="text-muted">{{ $requerimento->situacao }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $requerimento->total }}</span>
                            </li>
                            @empty
                            <li class="list-group-item text-center p-1">Nenhum dado disponível</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

       
    
    <div id="grafico-container" class="container mt-4 w-50 p-4 card-header bg-white p-2 rounded border shadow-sm mb-3">
    <div class="d-flex justify-content-between align-items-center mb-4 row justify-content-center">
        <label for="filtro" class="form-label me-2">Filtrar por:</label>
        <select id="filtro" class="form-select w-auto">
            <option value="situacao">Situação</option>
            <option value="tipo">Tipo de Requisição</option>
        </select>
    </div>
    <canvas id="graficoRequerimentos"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("graficoRequerimentos").getContext("2d");

        const labelsSituacao = JSON.parse('{!! $requerimentos->pluck("situacao") !!}');
        const dataSituacao = JSON.parse('{!! $requerimentos->pluck("total") !!}');
        const labelsTipo = JSON.parse('{!! $requerimentos->pluck("tipo_requisicao") !!}');
        const dataTipo = JSON.parse('{!! $requerimentos->pluck("total") !!}');
        
        let chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labelsSituacao,
                datasets: [{
                    label: "Total de Requerimentos por Situação",
                    data: dataSituacao,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.5)",
                        "rgba(54, 162, 235, 0.5)",
                        "rgba(255, 206, 86, 0.5)",
                        "rgba(75, 192, 192, 0.5)",
                        "rgba(153, 102, 255, 0.5)",
                        "rgba(255, 159, 64, 0.5)"
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)"
                    ],
                    borderWidth: 1,
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
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

        document.getElementById("filtro").addEventListener("change", function () {
            const isSituacao = this.value === "situacao";
            chart.data.labels = isSituacao ? labelsSituacao : labelsTipo;
            chart.data.datasets[0].data = isSituacao ? dataSituacao : dataTipo;
            chart.data.datasets[0].label = isSituacao ? "Total de Requerimentos por Situação" : "Total de Requerimentos por Tipo";
            chart.update();
        });
    });
</script>

</x-appcradt>
