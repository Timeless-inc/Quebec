<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Requerimentos Processados</title>
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
        <h1>Relatório de Requerimentos Processados</h1>
        <div class="user-info">{{ $user->name }}</div>
        <div class="user-info">{{ $periodoTexto }}</div>
        <div class="user-info">Gerado em: {{ $dataGeracao }}</div>
    </div>
    
    <div style="display: flex; justify-content: space-between;">
        <div class="highlight" style="width: 30%;">
            <span class="highlight-value">{{ $data['totalRecebidos'] }}</span>
            <span class="highlight-label">Total Recebidos</span>
        </div>
        
        <div class="highlight" style="width: 30%;">
            <span class="highlight-value">{{ $data['totalProcessados'] }}</span>
            <span class="highlight-label">Total Processados</span>
        </div>

        <div class="highlight" style="width: 30%;">
            <span class="highlight-value">{{ $data['totalDevolvidos'] }}</span>
            <span class="highlight-label">Total Devolvidos</span>
        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 15px;">
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
            <span class="highlight-label">Tempo Médio de Processamento</span>
        </div>
    </div>
    
    <h2>Requerimentos Processados Recentemente</h2>
    @if(count($data['requerimentosProcessados']) > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Aluno</th>
                <th>Encaminhado por</th>
                <th>Status Final</th>
                <th>Data Processamento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['requerimentosProcessados']->take(15) as $req)
            <tr>
                <td>#{{ $req->requerimento->id }}</td>
                <td>{{ \Illuminate\Support\Str::limit($req->requerimento->tipoRequisicao, 25) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($req->requerimento->nomeCompleto, 25) }}</td>
                <td>{{ $req->sender->name ?? 'N/A' }}</td>
                <td>
                    @if($req->status == 'finalizado')
                    <span style="color: #00913F;">Deferido</span> 
                    @elseif($req->status == 'indeferido')
                    <span style="color: #AF1B3F;">Indeferido</span> 
                    @else
                    {{ ucfirst($req->status) }}
                    @endif
                </td>
                <td>{{ $req->updated_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Nenhum requerimento processado no período.</p>
    @endif
    
    <div class="page-break"></div>
    
    <h2>Tipos de Requerimentos Mais Frequentes</h2>
    <div class="chart-container">
        @if(count($data['tiposFrequentes']) > 0)
            @foreach($data['tiposFrequentes'] as $tipo)
                <div class="bar-container">
                    <span class="bar-label">{{ \Illuminate\Support\Str::limit($tipo->tipoRequisicao, 25) }}</span>
                    <div class="bar" style="width: {{ min(300, $tipo->total * 30) }}px; background-color: #00913F;">
                        <span class="bar-value">{{ $tipo->total }}</span>
                    </div>
                </div>
            @endforeach
        @else
            <p>Nenhum dado disponível.</p>
        @endif
    </div>
    
    <h2>Status dos Requerimentos</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @php $totalStatus = $data['estatisticasPorStatus']->sum('total') @endphp
            @foreach($data['estatisticasPorStatus'] as $situacao)
            <tr>
                <td>
                    @if($situacao->status == 'finalizado')
                    <span style="color: #00913F;">Deferido</span> 
                    @elseif($situacao->status == 'indeferido')
                    <span style="color: #AF1B3F;">Indeferido</span> 
                    @elseif($situacao->status == 'encaminhado')
                    <span style="color: #9b59b6;">Encaminhado</span>
                    @elseif($situacao->status == 'devolvido')
                    <span style="color: #e91e63;">Devolvido</span>
                    @else
                    {{ ucfirst($situacao->status) }}
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
    
    <h2>Eficiência no Processamento</h2>
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

    @if(count($data['requerimentosDevolvidos']) > 0)
    <div class="page-break"></div>
    
    <h2>Requerimentos Devolvidos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Aluno</th>
                <th>Motivo da Devolução</th>
                <th>Data Devolução</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['requerimentosDevolvidos']->take(10) as $dev)
            <tr>
                <td>#{{ $dev->requerimento->id }}</td>
                <td>{{ \Illuminate\Support\Str::limit($dev->requerimento->tipoRequisicao, 25) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($dev->requerimento->nomeCompleto, 25) }}</td>
                <td>{{ $dev->internal_message ?: 'Não informado' }}</td>
                <td>{{ $dev->updated_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>Este relatório contém informações sobre os requerimentos processados pelo coordenador.</p>
        <p>SRE - Sistema de Requerimentos do Estudante | Gerado em {{ $dataGeracao }}</p>
    </div>
</body>
</html>
