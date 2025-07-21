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
'assinatura'
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
            margin-bottom: 10px;
        }

        .logo {
            width: 760px;
            height: 65px;
            margin-right: 20px;
        }

        h4 {
            margin-top: -5;
            margin-bottom: -1%;
            font-size: 16px;
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

        th,
        td {
            border: 1px solid #000000;
            min-height: 20px;
            text-align: left;
            font-size: 10px;
            padding: 2px;
            margin: 0;
        }

        td {
            font-size: 12px;
            /* Keep font size but remove background */
        }

        /* Specific classes for green background */
        .green-background {
            background-color: #e3ecc5;
        }

        .campus-column,
        .nome-column,
        .matricula-column,
        .periodo-column,
        .curso-column,
        .turno-column,
        .contato-column,
        .cpf-column,
        .identidade-column,
        .expedidor-column {
            width: auto;
            /* Reset widths if needed */
        }

        .campus-column {
            width: 25%;
        }

        .nome-column {
            width: 50%;
        }

        .matricula-column {
            width: 20%;
        }

        .periodo-column {
            width: 13%;
        }

        .curso-column {
            width: 20%;
        }

        .turno-column {
            width: 10.5%;
        }

        .contato-column {
            width: auto;
        }

        .cpf-column {
            width: 20%;
        }

        .identidade-column {
            width: 13%;
        }

        .expedidor-column {
            width: auto;
        }

        .vinculo-column {
            font-size: 12px;
            padding-left: 8px;
            border-color: white;
        }

        .botao-column {
            width: 5%;
            font-size: 13px;
            text-align: center;
        }

        .iten {
            width: 52%;
            font-size: 8.5px;
            padding-left: 2px;
            text-align: center;
        }

        .iten-column {
            width: 52%;
            font-size: 8.5px;
            padding-left: 2px;
            text-align: left;
        }

        .anexo-column {
            width: 7%;
            font-size: 8.5px;
            text-align: center;
        }

        .documentacao {
            width: auto;
            font-size: 8.5px;
            padding-left: 2px;
            text-align: center;
        }

        .documentacao-column {
            width: auto;
            font-size: 8.5px;
            padding-left: 2px;
            text-align: left;
        }

        .observacoes-column-conteudo {
            width: auto;
            padding-left: 2%;
            text-align: initial;
            vertical-align: middle;
            line-height: 1.5;
            background-color: #e3ecc5;
            /* Green background only for Observações */
        }

        .observacoes-column {
            width: auto;
            font-size: 16px;
            padding-left: 2%;
            text-align: initial;
            vertical-align: middle;
            line-height: 1.5;
            word-wrap: break-word;
            border: 1px solid #000000;
        }

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
                <td class="campus-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->campus }}</td>
                <td class="nome-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->nomeCompleto }}</td>
                <td class="matricula-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->matricula }}</td>
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
                <td class="periodo-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->periodo }}</td>
                <td class="curso-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->curso }}</td>
                <td class="turno-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->turno }}</td>
                <td class="contato-column observacoes-column-conteudo">{{ $requerimento->celular }} / {{ $requerimento->email }}</td>
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
                <th class="vinculo-column" rowspan="2">
                    ({{ $requerimento->situacao == 'Matriculado' ? 'X' : ' ' }}) Matriculado
                    ({{ $requerimento->situacao == 'Graduado' ? 'X' : ' ' }}) Graduado
                    ({{ $requerimento->situacao == 'Desvinculado' ? 'X' : ' ' }}) Desvinculado
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cpf-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->cpf }}</td>
                <td class="identidade-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->rg }}</td>
                <td class="expedidor-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->orgaoExpedidor }}</td>
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
                <th class="iten">ITENS</th>
                <th class="anexo-column">ANEXOS -------></th>
                <th class="documentacao">DOCUMENTAÇÃO EXIGIDA (ANEXOS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Admissão por Transferência e Análise Curricular' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Admissão por Transferência e Análise Curricular (anexos) - Solicitação no Protocolo Geral</td>
                <td class="anexo-column">c,f,g,h,i</td>
                <td class="documentacao-column">a - Atestado Médico</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Ajuste de Matrícula Semestral' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Ajuste de Matrícula Semestral</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">b - Cópia da CTPS - Identificação e Contrato</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Autorização para cursar disciplinas em outras Instituições de Ensino Superior' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Autorização para cursar disciplinas em outras Instituições de Ensino Superior (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">c - Declaração de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Cancelamento de Matrícula' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Cancelamento de Matrícula</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">d - Declaração da Empresa com o respectivo horário</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Cancelamento de Disciplina' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Cancelamento de Disciplina (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">e - Guia de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Certificado de Conclusão' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">
                    Certificado de Conclusão - Ano ({{ $requerimento->tipoRequisicao == 'Certificado de Conclusão' && isset($requerimento->dadosExtra['ano']) ? $requerimento->dadosExtra['ano'] : '' }}) Semestre ({{ $requerimento->tipoRequisicao == 'Certificado de Conclusão' && isset($requerimento->dadosExtra['semestre']) ? $requerimento->dadosExtra['semestre'] : '' }})
                </td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">f - Histórico Escolar do Ensino Fundamental (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Certidão - Autenticidade' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Certidão - Autenticidade (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">g - Histórico Escolar do Ensino Médio (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Complementação de Matrícula' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Complementação de Matrícula (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">h - Histórico Escolar do Ensino Superior (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Cópia Xerox de Documento' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Cópia Xerox de Documento (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">i - Ementas das disciplinas cursadas com Aprovação</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Declaração de Colação de Grau e Tramitação de Diploma' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Declaração de Colação de Grau e Tramitação de Diploma (curso tecnológico)</td>
                <td class="anexo-column">a/b, d</td>
                <td class="documentacao-column">j - Declaração de Unidade Militar</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Declaração de Matrícula ou Matrícula Vínculo' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Declaração de Matrícula ou Matrícula Vínculo (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Declaração de Monitoria' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Declaração de Monitoria</td>
                <td class="anexo-column"></td>
                <th class="documentacao-column" rowspan="3" class="observacoes-column"><strong>OBSERVAÇÕES:</strong></th>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Declaração para Estágio' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">
                    Declaração para Estágio - Conclusão Ano ({{ $requerimento->tipoRequisicao == 'Declaração para Estágio' && isset($requerimento->dadosExtra['ano']) ? $requerimento->dadosExtra['ano'] : '' }}) Semestre ({{ $requerimento->tipoRequisicao == 'Declaração para Estágio' && isset($requerimento->dadosExtra['semestre']) ? $requerimento->dadosExtra['semestre'] : '' }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">
                    Diploma 1a Via ({{ $requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia' && isset($requerimento->dadosExtra['via']) && $requerimento->dadosExtra['via'] == '1ª via' ? 'X' : '' }}) 2a ({{ $requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia' && isset($requerimento->dadosExtra['via']) && $requerimento->dadosExtra['via'] == '2ª via' ? 'X' : '' }}) - Conclusão Ano ({{ $requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia' && isset($requerimento->dadosExtra['ano']) ? $requerimento->dadosExtra['ano'] : '' }}) Semestre ({{ $requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia' && isset($requerimento->dadosExtra['semestre']) ? $requerimento->dadosExtra['semestre'] : '' }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Dispensa da prática de Educação Física' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Dispensa da prática de Educação Física (anexos)</td>
                <td class="anexo-column">a/j</td>
                <td class="documentacao-column observacoes-column-conteudo" rowspan="14" style="vertical-align: top;">
                    <br>
                    @php
                    $observacoes = $requerimento->observacoes;
                    $partes = explode("\n\n", $observacoes);
                    $infoRequerimento = isset($partes[0]) ? $partes[0] : $observacoes;
                    $obsUsuario = isset($partes[1]) ? $partes[1] : '';
                    @endphp
                    {{ $infoRequerimento }}<br>
                    @if($obsUsuario)
                    <hr style="border: 1px solid #ccc; margin: 5px 0;">
                    <strong>Observações do Usuário:</strong><br>
                    {{ $obsUsuario }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Declaração Tramitação de Diploma' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Declaração Tramitação de Diploma (técnico)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Ementa de disciplina' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Ementa de disciplina - (especifique)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Guia de Transferência' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Guia de Transferência</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Histórico Escolar' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">
                    Histórico Escolar - Ano ({{ $requerimento->tipoRequisicao == 'Histórico Escolar' && isset($requerimento->dadosExtra['ano']) ? $requerimento->dadosExtra['ano'] : '' }}) Semestre ({{ $requerimento->tipoRequisicao == 'Histórico Escolar' && isset($requerimento->dadosExtra['semestre']) ? $requerimento->dadosExtra['semestre'] : '' }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Isenção de disciplinas cursadas' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Isenção de disciplinas cursadas (anexo)</td>
                <td class="anexo-column">f/g/h,i</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Justificativa de falta(s) ou prova 2º chamada' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Justificativa de falta(s) ou prova 2 chamada (anexos)</td>
                <td class="anexo-column">a,d,i</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Matriz curricular' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Matriz curricular</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Reabertura de Matrícula' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Reabertura de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">
                    Reintegração ({{ $requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC' && isset($requerimento->dadosExtra['opcao_reintegracao']) && $requerimento->dadosExtra['opcao_reintegracao'] == 'Reintegração' ? 'X' : '' }})
                    Estágio ({{ $requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC' && isset($requerimento->dadosExtra['opcao_reintegracao']) && $requerimento->dadosExtra['opcao_reintegracao'] == 'Estágio' ? 'X' : '' }})
                    Entrega do Relatório de Estágio ({{ $requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC' && isset($requerimento->dadosExtra['opcao_reintegracao']) && $requerimento->dadosExtra['opcao_reintegracao'] == 'Entrega do Relatório de Estágio' ? 'X' : '' }})
                    TCC ({{ $requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC' && isset($requerimento->dadosExtra['opcao_reintegracao']) && $requerimento->dadosExtra['opcao_reintegracao'] == 'TCC' ? 'X' : '' }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Reintegração para Cursar' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Reintegração para Cursar (Solicitar no Protocolo Geral)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Solicitação de Conselho de Classe' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Solicitação de Conselho de Classe</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Trancamento de Matrícula' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Trancamento de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Transferência de Turno' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Transferência de Turno (especifique turno)</td>
                <td class="anexo-column">a/j</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabela Lançamento de Nota -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">LANÇAMENTO DE NOTA:</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular: {{ $requerimento->tipoRequisicao == 'Lançamento de Nota' ? ($requerimento->dadosExtra['componente_curricular'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column">{{ $requerimento->tipoRequisicao == 'Lançamento de Nota' ? '[X]' : '[O]' }}</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Lançamento de Nota' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '1ª unidade' ? 'X' : ' ' }}) 1 unidade
                    ({{ $requerimento->tipoRequisicao == 'Lançamento de Nota' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '2ª unidade' ? 'X' : ' ' }}) 2 unidade
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor: {{ $requerimento->tipoRequisicao == 'Lançamento de Nota' ? ($requerimento->dadosExtra['nome_professor'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Lançamento de Nota' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '3ª unidade' ? 'X' : ' ' }}) 3 unidade
                    ({{ $requerimento->tipoRequisicao == 'Lançamento de Nota' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '4ª unidade' ? 'X' : ' ' }}) 4 unidade
                    ({{ $requerimento->tipoRequisicao == 'Lançamento de Nota' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == 'Exame Final' ? 'X' : ' ' }}) Exame Final
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre: {{ $requerimento->tipoRequisicao == 'Lançamento de Nota' ? ($requerimento->dadosExtra['ano_semestre'] ?? 'Não informado') : '' }}</th>
            </tr>
        </thead>
    </table>

    <!-- Tabela Revisão de Notas -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">REVISÃO DE NOTAS</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular: {{ $requerimento->tipoRequisicao == 'Revisão de Notas' ? ($requerimento->dadosExtra['componente_curricular'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column">{{ $requerimento->tipoRequisicao == 'Revisão de Notas' ? '[X]' : '[O]' }}</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Notas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '1ª unidade' ? 'X' : ' ' }}) 1 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Notas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '2ª unidade' ? 'X' : ' ' }}) 2 unidade
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor: {{ $requerimento->tipoRequisicao == 'Revisão de Notas' ? ($requerimento->dadosExtra['nome_professor'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Notas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '3ª unidade' ? 'X' : ' ' }}) 3 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Notas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '4ª unidade' ? 'X' : ' ' }}) 4 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Notas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == 'Exame Final' ? 'X' : ' ' }}) Exame Final
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre: {{ $requerimento->tipoRequisicao == 'Revisão de Notas' ? ($requerimento->dadosExtra['ano_semestre'] ?? 'Não informado') : '' }}</th>
            </tr>
        </thead>
    </table>

    <!-- Tabela Revisão de Faltas -->
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 11px; padding-left: 4%;">REVISÃO DE FALTAS</th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do componente curricular: {{ $requerimento->tipoRequisicao == 'Revisão de Faltas' ? ($requerimento->dadosExtra['componente_curricular'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column">{{ $requerimento->tipoRequisicao == 'Revisão de Faltas' ? '[X]' : '[O]' }}</th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Faltas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '1ª unidade' ? 'X' : ' ' }}) 1 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Faltas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '2ª unidade' ? 'X' : ' ' }}) 2 unidade
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Nome do professor: {{ $requerimento->tipoRequisicao == 'Revisão de Faltas' ? ($requerimento->dadosExtra['nome_professor'] ?? 'Não informado') : '' }}</th>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column" style="font-size: 8.5px; padding-left: 4%;">
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Faltas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '3ª unidade' ? 'X' : ' ' }}) 3 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Faltas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == '4ª unidade' ? 'X' : ' ' }}) 4 unidade
                    ({{ $requerimento->tipoRequisicao == 'Revisão de Faltas' && isset($requerimento->dadosExtra['unidade']) && $requerimento->dadosExtra['unidade'] == 'Exame Final' ? 'X' : ' ' }}) Exame Final
                </th>
                <th class="documentacao-column" style="font-size: 8.5px; padding-left: 4%;">Ano / Semestre: {{ $requerimento->tipoRequisicao == 'Revisão de Faltas' ? ($requerimento->dadosExtra['ano_semestre'] ?? 'Não informado') : '' }}</th>
            </tr>
        </thead>
    </table>

    <!-- Continuação da Tabela 4: Opções de Requerimento -->
    <table>
        <tbody>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Tempo de escolaridade' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Tempo de escolaridade</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $requerimento->tipoRequisicao == 'Outros' ? '[X]' : '[O]' }}</td>
                <td class="iten-column">Outros (relatar em OBSERVAÇÕES)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p><strong>Assinatura Digital:</strong> {{ $assinatura }}</p>
        <p>Este documento foi autenticado automaticamente e é válido para comprovação.</p>
    </div>

</body>

</html>