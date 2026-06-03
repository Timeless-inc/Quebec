<title>SRE - {{ $roleName ?? 'Diretor Geral' }} - Relatórios</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@php
    $rpx = $routePrefix ?? 'diretor-geral';
    $cs = $cargoSlug ?? null;
    $params = $rpx === 'painel' ? ['cargoSlug' => $cs] : [];
@endphp

<x-app-diretor-geral-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Relatórios') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Gere PDFs e acompanhe os indicadores de processamento de {{ $roleName ?? 'Diretor Geral' }}.
                </p>
            </div>
            <span class="inline-flex w-fit items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                <i class="fas fa-user-shield mr-2"></i>{{ $roleName ?? 'Diretor Geral' }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Relatórios disponíveis</h3>
                    <p class="mt-1 text-sm text-gray-500">Selecione o tipo de relatório e o período que melhor responde à análise.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 lg:grid-cols-3">
                    <div class="group flex min-h-[260px] flex-col overflow-hidden rounded-xl border border-blue-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 text-white">
                                    <i class="fas fa-chart-bar text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">Requerimentos Processados</h3>
                                    <p class="mt-1 text-sm text-blue-100">Atividades por status</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="mb-5 text-sm leading-6 text-gray-600">
                                Visualize os requerimentos processados, incluindo deferidos, indeferidos e devolvidos.
                            </p>
                            <div class="dropdown mt-auto">
                                <button class="dropdown-toggle inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-pdf mr-2"></i>Gerar relatório
                                </button>
                                <ul class="dropdown-menu w-100">
                                    <li><a class="dropdown-item" href="{{ route($rpx.'.reports.processed', array_merge($params, ['period' => 'mes-atual'])) }}">Mês atual</a></li>
                                    <li><a class="dropdown-item" href="{{ route($rpx.'.reports.processed', array_merge($params, ['period' => 'mes-anterior'])) }}">Mês anterior</a></li>
                                    <li><a class="dropdown-item" href="{{ route($rpx.'.reports.processed', array_merge($params, ['period' => 'ano-atual'])) }}">Ano atual</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="openCustomPeriodModal('processed')">Período personalizado</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="group flex min-h-[260px] flex-col overflow-hidden rounded-xl border border-emerald-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="bg-gradient-to-r from-emerald-600 to-green-500 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 text-white">
                                    <i class="fas fa-calendar-alt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">Relatório por Período</h3>
                                    <p class="mt-1 text-sm text-emerald-100">Análise por intervalo</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="mb-5 text-sm leading-6 text-gray-600">
                                Escolha uma data inicial e final para gerar um relatório personalizado.
                            </p>
                            <button class="mt-auto inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-700" onclick="openCustomPeriodModal('period')">
                                <i class="fas fa-filter mr-2"></i>Configurar período
                            </button>
                        </div>
                    </div>

                    <div class="group flex min-h-[260px] flex-col overflow-hidden rounded-xl border border-purple-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 text-white">
                                    <i class="fas fa-chart-pie text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">Estatísticas</h3>
                                    <p class="mt-1 text-sm text-purple-100">Dados consolidados</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="mb-5 text-sm leading-6 text-gray-600">
                                Consulte totais recebidos, processados, devolvidos e a evolução mensal.
                            </p>
                            <button class="mt-auto inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-purple-700" onclick="showStatistics()">
                                <i class="fas fa-chart-line mr-2"></i>Ver estatísticas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customPeriodModal" tabindex="-1" aria-labelledby="customPeriodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-xl border-0 shadow-2xl">
                <div class="modal-header border-0 bg-gray-50 px-5 py-4">
                    <div>
                        <h5 class="modal-title text-base font-semibold text-gray-900" id="customPeriodModalLabel">Selecionar período</h5>
                        <p class="mb-0 mt-1 text-sm text-gray-500">Informe o intervalo para geração do relatório.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body px-5 py-4">
                    <form id="customPeriodForm" class="space-y-4">
                        <div>
                            <label for="startDate" class="mb-1 block text-sm font-medium text-gray-700">Data inicial</label>
                            <input type="date" class="form-control rounded-lg border-gray-300" id="startDate" name="startDate" required>
                        </div>
                        <div>
                            <label for="endDate" class="mb-1 block text-sm font-medium text-gray-700">Data final</label>
                            <input type="date" class="form-control rounded-lg border-gray-300" id="endDate" name="endDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-gray-100 px-5 py-4">
                    <button type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-indigo-700" id="generateCustomPeriodReport">
                        <i class="fas fa-file-pdf mr-2"></i>Gerar relatório
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-xl border-0 shadow-2xl">
                <div class="modal-header border-0 bg-gray-50 px-5 py-4">
                    <div>
                        <h5 class="modal-title text-base font-semibold text-gray-900" id="statisticsModalLabel">Estatísticas de processamento</h5>
                        <p class="mb-0 mt-1 text-sm text-gray-500">Resumo consolidado da atividade recente.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body px-5 py-5">
                    <div id="statisticsContent">
                        <div class="flex items-center justify-center py-10 text-gray-500">
                            <div class="spinner-border mr-3" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            Carregando estatísticas...
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
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const formatDate = (date) => {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            };
            document.getElementById('startDate').value = formatDate(firstDayOfMonth);
            document.getElementById('endDate').value = formatDate(today);
            modal.show();
        }

        function showStatistics() {
            const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
            modal.show();
            fetch('{{ isset($routePrefix) && $routePrefix === "painel" ? route("painel.reports.statistics", ["cargoSlug" => $cargoSlug]) : route("diretor-geral.reports.statistics") }}')
                .then(r => r.json())
                .then(data => {
                    const rate = data.totalEncaminhados > 0 ? Math.round((data.totalProcessados / data.totalEncaminhados) * 100) : 0;
                    const content = `
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-center">
                                <p class="text-3xl font-semibold text-blue-700">${data.totalProcessados}</p>
                                <p class="mt-1 text-sm font-medium text-blue-900">Total processados</p>
                            </div>
                            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-4 text-center">
                                <p class="text-3xl font-semibold text-indigo-700">${data.totalEncaminhados}</p>
                                <p class="mt-1 text-sm font-medium text-indigo-900">Total recebidos</p>
                            </div>
                            <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-center">
                                <p class="text-3xl font-semibold text-amber-700">${data.totalDevolvidos}</p>
                                <p class="mt-1 text-sm font-medium text-amber-900">Total devolvidos</p>
                            </div>
                            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-center">
                                <p class="text-3xl font-semibold text-emerald-700">${rate}%</p>
                                <p class="mt-1 text-sm font-medium text-emerald-900">Taxa de processamento</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h6 class="mb-3 text-sm font-semibold text-gray-900">Atividade nos últimos 6 meses</h6>
                            <div class="overflow-hidden rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Mês</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Processados</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        ${data.estatisticasMensais.map(i => `<tr><td class="px-4 py-3 text-sm text-gray-700">${i.mes}</td><td class="px-4 py-3 text-sm font-semibold text-gray-900">${i.total}</td></tr>`).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                    document.getElementById('statisticsContent').innerHTML = content;
                })
                .catch(() => {
                    document.getElementById('statisticsContent').innerHTML = '<div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">Erro ao carregar estatísticas.</div>';
                });
        }

        document.getElementById('generateCustomPeriodReport').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (!startDate || !endDate) {
                alert('Selecione as datas.');
                return;
            }
            if (new Date(startDate) > new Date(endDate)) {
                alert('Data inicial deve ser anterior à final.');
                return;
            }
            let url = '';
            if (currentReportType === 'processed') {
                url = `{{ isset($routePrefix) && $routePrefix === 'painel' ? route('painel.reports.processed', ['cargoSlug' => $cargoSlug]) : route('diretor-geral.reports.processed') }}?period=personalizado&startDate=${startDate}&endDate=${endDate}`;
            } else {
                url = `{{ isset($routePrefix) && $routePrefix === 'painel' ? route('painel.reports.period', ['cargoSlug' => $cargoSlug]) : route('diretor-geral.reports.period') }}?startDate=${startDate}&endDate=${endDate}`;
            }
            bootstrap.Modal.getInstance(document.getElementById('customPeriodModal')).hide();
            window.location.href = url;
        });
    </script>
</x-app-diretor-geral-layout>
