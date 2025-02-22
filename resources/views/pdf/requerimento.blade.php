@props([
'id',
'nome',
'matricula',
'email',
'cpf',
'datas',
'andamento',
'anexos',
'observacoes',
'status',
'requerimento',
'dados',
])

<!DOCTYPE html>
<html>
<head>
    <title>Requerimento</title>
    <style>
        body { font-family: 'Roboto', sans-serif;}
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 2px; font-size: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header-title { margin-top: -6%; margin-bottom: 0%; }
        .header-subtitle { margin-top: -1%; margin-bottom: 0%; }
        .highlight { background-color: #e3ecc5; }
        .signature { margin-top: 20px; padding: 10px; background-color: #f9f9f9; border: 1px dashed #ccc; }
        ul { list-style: none; padding: 0; }
        ul li { padding: 5px 0; }
    </style>
</head>
<body>
    <img id="logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo-instituto.png'))) }}" alt="Logo do IFPE">
    <h3 class="header-subtitle">REQUERIMENTO - ALUNO(A)</h3>
    <table>
        <thead>
            <tr>
                <th>CAMPUS</th>
                <th>NOME DO(A) ALUNO(A) (letra de forma)</th>
                <th>N° DE MATRÍCULA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->campus }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->nomeCompleto }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->matricula }}</td>
            </tr>
        </tbody>
    </table>
    
    <table>
        <thead>
            <tr>
                <th>PER/MOD/SÉRIE</th>
                <th>CURSO / MODALIDADE</th>
                <th>TURNO</th>
                <th>TELEFONE FIXO / TELEFONE CELULAR / E-MAIL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->periodo }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->curso }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->turno }}</td>
                <td class="highlight">{{ $requerimento->celular }} / {{ $requerimento->email }}</td>
            </tr>
        </tbody>
    </table>
    
    <table>
        <thead>
            <tr>
                <th>CPF</th>
                <th>IDENTIDADE</th>
                <th>ÓRGÃO EXPED.</th>
                <th>VÍNCULO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->cpf }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->rg }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->orgaoExpedidor }}</td>
                <td class="highlight" style="text-transform: uppercase">{{ $requerimento->situacao }}</td>
               
            </tr>
        </tbody>
    </table>
    
    <table>
    <caption><h4>Opções de Requerimento</h4></caption>
    <thead>
        <tr>
            <th>Requerimento</th>
            <th>Anexos</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Admissão por Transferência e Análise Curricular</td>
            <td>c, f, g, h, i</td>
        </tr>
        <tr>
            <td>Ajuste de Matrícula Semestral</td>
            <td>b</td>
        </tr>
        <tr>
            <td>Autorização para cursar disciplinas externas</td>
            <td>c</td>
        </tr>
        <tr>
            <td>Cancelamento de Matrícula</td>
            <td>d</td>
        </tr>
        <tr>
            <td>Cancelamento de Disciplina</td>
            <td>e</td>
        </tr>
        <tr>
            <td>Certificado de Conclusão</td>
            <td>f</td>
        </tr>
        <tr>
            <td>Complementação de Matrícula</td>
            <td>h</td>
        </tr>
        <tr>
            <td>Declaração de Colação de Grau</td>
            <td>a/b, d</td>
        </tr>
        <tr>
            <td>Reabertura de Matrícula</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Solicitação de Conselho de Classe</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Trancamento de Matrícula</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Transferência de Turno</td>
            <td>a/j</td>
        </tr>
    </tbody>
</table>
    
    <p><strong>OBSERVAÇÕES:</strong> {{ $requerimento->observacoes }}</p>
    <p>Data: _________________________</p>
    <p>PROTOCOLO nº: CGCA / CRE / SRE</p>

    <div class="signature">
        <p><strong>Assinatura Digital:</strong> {{ $assinatura }}</p>
        <p>Este documento foi autenticado automaticamente e é válido para comprovação.</p>
    </div>

</body>
</html>