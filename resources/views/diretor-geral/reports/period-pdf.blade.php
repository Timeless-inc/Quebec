<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório por Período</title>
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
        .page-break {
            page-break-after: always;
        }
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .summary-card {
            width: 22%;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #00913F;
            margin-bottom: 5px;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Detalhado por Período</h1>
        <div class="user-info">{{ $user->name }}</div>
        <div class="user-info">{{ $periodoTexto }}</div>
        <div class="user-info">Gerado em: {{ $dataGeracao }}</div>
    </div>
    
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-number">{{ $data['totalRecebidos'] }}</div>
            <div class="summary-label">Recebidos</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ $data['totalProcessados'] }}</div>
            <div class="summary-label">Processados</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ $data['totalDevolvidos'] }}</div>
            <div class="summary-label">Devolvidos</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">
                @if($data['totalRecebidos'] > 0)
                    {{ round(($data['totalProcessados'] / $data['totalRecebidos']) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
            <div class="summary-label">Taxa Processamento</div>
        </div>
    </div>

    <h2>Todos os Requerimentos Recebidos</h2>
    @if(count($data['requerimentosRecebidos']) > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Aluno</th>
                <th>Encaminhado por</th>
                <th>Status Atual</th>
                <th>Data Recebimento</th>
                <th>Data Processamento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['requerimentosRecebidos'] as $req)
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
                    @elseif($req->status == 'encaminhado')
                    <span style="color: #9b59b6;">Em Análise</span>
                    @elseif($req->status == 'devolvido')
                    <span style="color: #e91e63;">Devolvido</span>
                    @else
                    {{ ucfirst($req->status) }}
                    @endif
                </td>
                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if(in_array($req->status, ['finalizado', 'indeferido', 'devolvido']))
                        {{ $req->updated_at->format('d/m/Y H:i') }}
                    @else
                        Em andamento
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Nenhum requerimento recebido no período especificado.</p>
    @endif

    @if(count($data['requerimentosDevolvidos']) > 0)
    <div class="page-break"></div>
    
    <h2>Detalhes dos Requerimentos Devolvidos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Aluno</th>
                <th>Encaminhado por</th>
                <th>Motivo da Devolução</th>
                <th>Data Devolução</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['requerimentosDevolvidos'] as $dev)
            <tr>
                <td>#{{ $dev->requerimento->id }}</td>
                <td>{{ \Illuminate\Support\Str::limit($dev->requerimento->tipoRequisicao, 25) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($dev->requerimento->nomeCompleto, 25) }}</td>
                <td>{{ $dev->sender->name ?? 'N/A' }}</td>
                <td>{{ $dev->internal_message ?: 'Não informado' }}</td>
                <td>{{ $dev->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2>Resumo Estatístico</h2>
    <table>
        <thead>
            <tr>
                <th>Métrica</th>
                <th>Valor</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total de Requerimentos Recebidos</td>
                <td>{{ $data['totalRecebidos'] }}</td>
                <td>Requerimentos encaminhados para análise</td>
            </tr>
            <tr>
                <td>Total de Requerimentos Processados</td>
                <td>{{ $data['totalProcessados'] }}</td>
                <td>Requerimentos finalizados (deferidos ou indeferidos)</td>
            </tr>
            <tr>
                <td>Total de Requerimentos Devolvidos</td>
                <td>{{ $data['totalDevolvidos'] }}</td>
                <td>Requerimentos retornados para correção</td>
            </tr>
            <tr>
                <td>Taxa de Processamento</td>
                <td>
                    @if($data['totalRecebidos'] > 0)
                        {{ round(($data['totalProcessados'] / $data['totalRecebidos']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
                <td>Percentual de requerimentos efetivamente processados</td>
            </tr>
            <tr>
                <td>Taxa de Devolução</td>
                <td>
                    @if($data['totalRecebidos'] > 0)
                        {{ round(($data['totalDevolvidos'] / $data['totalRecebidos']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
                <td>Percentual de requerimentos devolvidos</td>
            </tr>
            <tr>
                <td>Tempo Médio de Processamento</td>
                <td>
                    @if($data['tempoMedioDias'] < 1)
                        Menos de 1 dia
                    @elseif($data['tempoMedioDias'] < 2)
                        Menos de 2 dias
                    @else
                        {{ round($data['tempoMedioDias']) }} dias
                    @endif
                </td>
                <td>Tempo médio entre recebimento e processamento</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p>Este relatório apresenta uma análise detalhada da atividade no período especificado.</p>
        <p>SRE - Sistema de Requerimentos do Estudante | Gerado em {{ $dataGeracao }}</p>
    </div>
</body>
</html>
