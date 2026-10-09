<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #00913F;
        }
        h1 {
            font-size: 20px;
            margin: 0 0 5px;
            color: #00913F;
        }
        .info {
            font-size: 12px;
            color: #444;
        }
        .chart {
            text-align: center;
            margin-bottom: 20px;
        }
        .chart img {
            max-width: 100%;
            max-height: 380px;
        }
        .chart.tall {
            page-break-after: always;
        }
        .chart.tall img {
            max-height: 760px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: {{ count($cabecalho) > 6 ? '9px' : '11px' }};
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px 7px;
            text-align: right;
        }
        th:first-child, td:first-child {
            text-align: left;
        }
        th {
            background-color: #00913F;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tfoot td {
            font-weight: bold;
            background-color: #eef7f1;
        }
        .empty {
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="info">Período: {{ $periodo }}</div>
        <div class="info">Gerado em {{ $dataGeracao }} por {{ $geradoPor }}</div>
    </div>

    @if($grafico)
        <div class="chart {{ $graficoAlto ? 'tall' : '' }}">
            <img src="{{ $grafico }}" alt="Gráfico do relatório">
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($cabecalho as $coluna)
                    <th>{{ $coluna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($linhas as $linha)
                <tr>
                    @foreach($linha as $celula)
                        <td>{{ is_float($celula) ? number_format($celula, 1, ',', '.') . '%' : $celula }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ count($cabecalho) }}">Nenhum dado disponível para o período.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                @foreach($rodape as $celula)
                    <td>{{ is_float($celula) ? number_format($celula, 1, ',', '.') . '%' : $celula }}</td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>
