<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Personalizado</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #00913F;
            position: relative;
        }
        .header-image {
            position: absolute;
            top: 0;
            right: 0;
            width: 70px;
        }
        h1 {
            font-size: 22px;
            margin: 0 0 5px;
            color: #00913F; 
        }
        h2 {
            font-size: 18px;
            margin: 20px 0 10px;
            color: #00913F;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .user-info {
            font-size: 14px;
            color: #444;
            margin-bottom: 5px;
        }
        .highlight {
            background-color: #f8f9fa;
            border-left: 4px solid #00913F;
            padding: 10px 15px;
            margin: 15px 0;
        }
        .highlight-value {
            font-size: 24px;
            font-weight: bold;
            color: #00913F;
            display: block;
            margin-bottom: 5px;
        }
        .highlight-label {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .stat-box {
            width: 45%;
            margin: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            box-sizing: border-box;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #00913F;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #777;
            text-align: center;
            padding-top: 10px;
            border-top: 2px solid #00913F;
        }
        .chart-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .bar-container {
            margin: 10px 0;
        }
        .bar-label {
            display: inline-block;
            width: 150px;
            font-size: 12px;
            margin-right: 10px;
        }
        .bar {
            display: inline-block;
            height: 20px;
            background-color: #00913F; 
            position: relative;
        }
        .bar-value {
            position: absolute;
            right: -25px;
            top: 2px;
            font-size: 10px;
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Personalizado de Atendimentos</h1>
        <div class="user-info">Servidor(a): {{ $user->name }}</div>
        <div class="user-info">Matrícula: {{ $user->matricula }}</div>
        <div class="user-info">{{ $periodoTexto }}</div>
        <div class="user-info">Gerado em: {{ $dataGeracao }}</div>
    </div>
    
    <div style="display: flex; justify-content: space-between;">
        <div class="highlight" style="width: 45%;">
            <span class="highlight-value">{{ $data['totalAtendimentos'] }}</span>
            <span class="highlight-label">Total de Atendimentos</span>
        </div>
        
        <div class="highlight" style="width: 45%;">
            <span class="highlight-value">
                @if($data['tempoMedioDias'] < 1)
                    Menos de 1 dia
                @elseif($data['tempoMedioDias'] < 2)
                    Menos de 2 dias
                @elseif($data['tempoMedioDias'] < 5)
                    Menos de 5 dias
                @elseif($data['tempoMedioDias'] < 7)
                    Menos de 7 dias
                @else
                    {{ round($data['tempoMedioDias']) }} dias
                @endif
            </span>
            <span class="highlight-label">Tempo Médio de Resolução</span>
        </div>
    </div>
    
    <h2>Últimos Atendimentos</h2>
    @php
        $atendimentosFiltrados = $data['ultimosAtendimentos']->filter(function($item) { 
            return $item->status !== 'em_andamento'; 
        });
        
        $atendimentosFiltrados = $atendimentosFiltrados->take(10);
    @endphp

    @if(count($atendimentosFiltrados) > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Data Abertura</th>
                <th>Data Resolução</th>
                <th>Status</th>
                <th>Duração</th>
            </tr>
        </thead>
        <tbody>
            @foreach($atendimentosFiltrados as $atendimento)
            <tr>
                <td>#{{ $atendimento->id }}</td>
                <td>{{ $atendimento->tipoRequisicao }}</td>
                <td>{{ $atendimento->created_at->format('d/m/Y') }}</td>
                <td>{{ $atendimento->resolved_at ? \Carbon\Carbon::parse($atendimento->resolved_at)->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    @if($atendimento->status == 'finalizado')
                    <span style="color: #00913F;">Finalizado</span> 
                    @elseif($atendimento->status == 'indeferido')
                    <span style="color: #AF1B3F;">Indeferido</span> 
                    @elseif($atendimento->status == 'pendente')
                    <span style="color: #e67e22;">Pendente</span>
                    @else
                    {{ $atendimento->status }}
                    @endif
                </td>
                <td>
                    @if($atendimento->resolved_at)
                        @php
                            $diffHours = $atendimento->created_at->diffInHours(\Carbon\Carbon::parse($atendimento->resolved_at));
                            $diffDays = $atendimento->created_at->diffInDays(\Carbon\Carbon::parse($atendimento->resolved_at));
                            $diffDaysExact = $diffHours / 24;
                        @endphp
                        
                        @if($diffDaysExact < 1)
                            <span>Menos de 1 dia</span>
                        @elseif($diffDaysExact < 2)
                            <span>Menos de 2 dias</span>
                        @elseif($diffDaysExact < 5)
                            <span>Menos de 5 dias</span>
                        @elseif($diffDaysExact < 7)
                            <span>Menos de 7 dias</span>
                        @else
                            <span>{{ round($diffDays) }} dias</span>
                        @endif
                    @else
                        <span>N/A</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Nenhum atendimento registrado.</p>
    @endif
    
    <div class="page-break"></div>
    
    <h2>Tipos de Requerimentos Mais Frequentes</h2>
    <div class="chart-container">
        @if(count($data['tiposFrequentes']) > 0)
            @foreach($data['tiposFrequentes'] as $tipo)
                <div class="bar-container">
                    <span class="bar-label">{{ \Illuminate\Support\Str::limit($tipo->tipoRequisicao, 25) }}</span>
                    <div class="bar" style="width: {{ min(300, $tipo->total * 30) }}px; background-color: #00913F;"> <!-- Verde IFPE -->
                        <span class="bar-value">{{ $tipo->total }}</span>
                    </div>
                </div>
            @endforeach
        @else
            <p>Nenhum dado disponível.</p>
        @endif
    </div>
    
    <h2>Situações Mais Comuns</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $situacoesFiltered = $data['situacoesFrequentes']->filter(function($item) { 
                    return $item->status !== 'em_andamento'; 
                });
                $totalStatus = $situacoesFiltered->sum('total') 
            @endphp
            @foreach($situacoesFiltered as $situacao)
            <tr>
                <td>
                    @if($situacao->status == 'finalizado')
                    <span style="color: #00913F;">Finalizado</span> 
                    @elseif($situacao->status == 'indeferido')
                    <span style="color: #AF1B3F;">Indeferido</span> 
                    @elseif($situacao->status == 'pendente')
                    <span style="color: #e67e22;">Pendente</span>
                    @else
                    {{ $situacao->status }}
                    @endif
                </td>
                <td>{{ $situacao->total }}</td>
                <td>{{ number_format(($situacao->total / max(1, $totalStatus)) * 100, 1) }}%</td>
            </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #e8f5e9;"> 
                <td>Total</td>
                <td>{{ $totalStatus }}</td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>
    
    <h2>Eficiência na Resolução</h2>
    <div class="chart-container">
        @if(count($data['resolucaoEficiente']) > 0)
            @foreach($data['resolucaoEficiente'] as $tempo)
                <div class="bar-container">
                    <span class="bar-label">{{ $tempo->faixa_tempo }}</span>
                    <div class="bar" style="width: {{ min(300, $tempo->total * 30) }}px; 
                        background-color: 
                        {{ $tempo->faixa_tempo == 'Menos de 1 dia' ? '#00913F' : 
                          ($tempo->faixa_tempo == '1 dia' ? '#2ecc71' : 
                          ($tempo->faixa_tempo == '2 dias' ? '#f1c40f' : 
                          ($tempo->faixa_tempo == '3-7 dias' ? '#e67e22' : '#AF1B3F'))) }}">
                        <span class="bar-value">{{ $tempo->total }}</span>
                    </div>
                </div>
            @endforeach
        @else
            <p>Nenhum dado disponível.</p>
        @endif
    </div>
    
    <div class="footer">
        <p>Este relatório contém informações personalizadas baseadas nos atendimentos realizados pelo servidor.</p>
        <p>SRE - Sistema de Requerimentos do Estudante | Gerado em {{ $dataGeracao }}</p>
    </div>
</body>
</html>