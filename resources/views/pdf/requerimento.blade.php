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
<html lang="pt-BR">
<head>
    <title>Requerimento</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        * {
            font-family: sans-serif;
        }
        body {
            margin-left: -35px;
            margin-right: -35px;
            margin-top: -40px;
            margin-bottom: -35px;
        }
        .header-container {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px; /* Espaço abaixo do logo e título */
        }
        .logo {
            width: 760px;
            height: 65px;
            margin-right: 20px; /* Espaço entre o logo e o título */
        }
        h4 {
            margin-top: -5;
            margin-bottom: -1%;
            font-size: 16px; /* Ajuste para maior destaque, como no PDF */
        }
        p.subtitle {
            font-size: 12px;
            margin-top: -1%;
            margin-bottom: 0%;
        }
        table {
            border-collapse: collapse;
            border: 1px solid #000000;
            text-align: left;
            width: 100%;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #000000;
            min-height: 20px; /* Mantido em 30px, como solicitado */
            text-align: left;
            font-size: 10px;
        }
        td {
            background-color: #e3ecc5;
            padding: 2px;
            margin: 0;
            font-size: 12px;
        }
        .campus-column { width: 25%; }
        .nome-column { width: 50%; }
        .matricula-column { width: 20%; }
        .periodo-column { width: 13%; }
        .curso-column { width: 20%; }
        .turno-column { width: 10.5%; }
        .contato-column { width: auto; }
        .cpf-column { width: 20%; }
        .identidade-column { width: 13%; }
        .expedidor-column { width: auto; }
        .vinculo-column { font-size: 12px; padding-left: 8px; border-color: white; }
        .botao-column { width: 5%; font-size: 13px; text-align: center; }
        .iten-column { width: 52%; font-size: 8.5px; padding-left: 2px; text-align: left; }
        .anexo-column { width: 7%; font-size: 8.5px; text-align: center; }
        .documentacao-column { width: auto; font-size: 8.5px; padding-left: 2px; text-align: left; }
        .observacoes-column { width: auto; font-size: 16px; padding-left: 2%; text-align: initial; vertical-align: middle; line-height: 1.5; word-wrap: break-word; background-color: #e3ecc5; border: 1px solid #000000; }
        .signature {
            margin-top: 5px;
            padding: 2px;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
        }
        hr {
            border: 1px solid #ccc;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <img class="logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo-instituto.png'))) }}" alt="Logo do IFPE">
        <div>
            <h4>REQUERIMENTO - ALUNO(A)</h4>
        </div>
    </div>

    <!-- Tabela 1: Campus, Nome, Matrícula -->
    <table>
        <thead>
            <tr>
                <th class="campus-column">CAMPUS</th>
                <th class="nome-column">NOME DO(A) ALUNO(A) (letra de forma)</th>
                <th class="matricula-column">N° DE MATRÍCULA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="campus-column" style="text-transform: uppercase">{{ $requerimento->campus }}</td>
                <td class="nome-column" style="text-transform: uppercase">{{ $requerimento->nomeCompleto }}</td>
                <td class="matricula-column" style="text-transform: uppercase">{{ $requerimento->matricula }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabela 2: Período, Curso, Turno, Contato -->
    <table>
        <thead>
            <tr>
                <th class="periodo-column">PER/MOD/SÉRIE</th>
                <th class="curso-column">CURSO / MODALIDADE</th>
                <th class="turno-column">TURNO</th>
                <th class="contato-column">TELEFONE FIXO / TELEFONE CELULAR / E-MAIL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="periodo-column" style="text-transform: uppercase">{{ $requerimento->periodo }}</td>
                <td class="curso-column" style="text-transform: uppercase">{{ $requerimento->curso }}</td>
                <td class="turno-column" style="text-transform: uppercase">{{ $requerimento->turno }}</td>
                <td class="contato-column">{{ $requerimento->celular }} / {{ $requerimento->email }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabela 3: CPF, RG, Órgão Expedidor, Vínculo -->
    <table>
        <thead>
            <tr>
                <th class="cpf-column">CPF</th>
                <th class="identidade-column">IDENTIDADE</th>
                <th class="expedidor-column">ORGÃO EXPED.</th>
                <th class="vinculo-column" rowspan="2">( ) Matriculado ( ) Graduado ( ) Desvinculado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cpf-column" style="text-transform: uppercase">{{ $requerimento->cpf }}</td>
                <td class="identidade-column" style="text-transform: uppercase">{{ $requerimento->rg }}</td>
                <td class="expedidor-column" style="text-transform: uppercase">{{ $requerimento->orgaoExpedidor }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabela 4: Opções de Requerimento -->
    <table>
        <thead>
            <tr>
                <th colspan="4" style="font-size: 14px; padding: 3px; text-align: left;">Marque a sua opção desejada abaixo</th>
            </tr>
            <tr>
                <th class="botao-column">[X]</th>
                <th class="iten-column">ITENS</th>
                <th class="anexo-column">ANEXOS -------></th>
                <th class="documentacao-column">DOCUMENTAÇÃO EXIGIDA (ANEXOS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Admissão por Transferência e Análise Curricular (anexos) - Solicitação no Protocolo Geral</td>
                <td class="anexo-column">c,f,g,h,i</td>
                <td class="documentacao-column">a - Atestado Médico</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Ajuste de Matrícula Semestral</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">b - Cópia da CTPS - Identificação e Contrato</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Autorização para cursar disciplinas em outras Instituições de Ensino Superior (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">c - Declaração de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Cancelamento de Matrícula</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">d - Declaração da Empresa com o respectivo horário</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Cancelamento de Disciplina (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">e - Guia de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Certificado de Conclusão - Ano ( ) Semestre ( )</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">f - Histórico Escolar do Ensino Fundamental (original)</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Certidão - Autenticidade (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">g - Histórico Escolar do Ensino Médio (original)</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Complementação de Matrícula (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">h - Histórico Escolar do Ensino Superior (original)</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Cópia Xerox de Documento (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">i - Ementas das disciplinas cursadas com Aprovação</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Declaração de Colação de Grau e Tramitação de Diploma (curso tecnológico)</td>
                <td class="anexo-column">a/b, d</td>
                <td class="documentacao-column">j - Declaração de Unidade Militar</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Declaração de Matrícula ou Matrícula Vínculo (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Declaração de Monitoria</td>
                <td class="anexo-column"></td>
                <th class="documentacao-column" rowspan="3" class="observacoes-column"><strong>OBSERVAÇÕES:</strong></th>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Declaração para Estágio - Conclusão Ano ( ) Semestre ( )</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Diploma 1a Via ( ) 2a ( ) - Conclusão Ano ( ) Semestre ( )</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Dispensa da prática de Educação Física (anexos)</td>
                <td class="anexo-column">a/j</td>
                <td class="documentacao-column" rowspan="14" style="vertical-align: top;">{{ $requerimento->observacoes }}</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Declaração Tramitação de Diploma (técnico)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Ementa de disciplina - (especifique)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Guia de Transferência</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Histórico Escolar - Ano ( ) Semestre ( )</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Isenção de disciplinas cursadas (anexo)</td>
                <td class="anexo-column">f/g/h,i</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Justificativa de falta(s) ou prova 2 chamada (anexos)</td>
                <td class="anexo-column">a,d,i</td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Matriz curricular</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Reabertura de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Reintegração para Cursar (Solicitar no Protocolo Geral)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Solicitação de Conselho de Classe</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Trancamento de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Transferência de Turno (especifique turno)</td>
                <td class="anexo-column">a/j</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabela Notas -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">LANÇAMENTO DE NOTA:</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular:</th>
            </tr>
            <tr>
                <th class="botao-column">[O]</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 1 unidade ( ) 2 unidade</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor:</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 3 unidade ( ) 4 unidade ( ) Exame Final</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre:</th>
            </tr>
        </thead>
    </table>
    
    <!-- Tabela Revisão de Notas -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">REVISÃO DE NOTAS</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular:</th>
            </tr>
            <tr>
                <th class="botao-column">[O]</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 1 unidade ( ) 2 unidade</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor:</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 3 unidade ( ) 4 unidade ( ) Exame Final</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre:</th>
            </tr>
        </thead>
    </table>

    <!-- Tabela Revisão de Faltas -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">REVISÃO DE FALTAS</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular:</th>
            </tr>
            <tr>
                <th class="botao-column">[O]</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 1 unidade ( ) 2 unidade</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor:</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">( ) 3 unidade ( ) 4 unidade ( ) Exame Final</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre:</th>
            </tr>
        </thead>
    </table>

    <!-- Continuação da Tabela 4: Opções de Requerimento -->
    <table>
        <tbody>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Tempo de escolaridade</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">[O]</td>
                <td class="iten-column">Outros (relatar abaixo em OBSERVAÇÕES)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <th class="cpf-column" colspan="4"><strong>OBSERVAÇÕES:</strong></th>
            </tr>
            <tr>
            <tr>
                <th class="iten-column" colspan="4">{{ $requerimento->observacoes }}</th>
            </tr>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p><strong>Assinatura Digital:</strong> {{ $assinatura }}</p>
        <p>Este documento foi autenticado automaticamente e é válido para comprovação.</p>
    </div>

</body>
</html>
