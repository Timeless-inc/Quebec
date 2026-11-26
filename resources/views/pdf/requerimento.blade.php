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
'urlVerificacao',
'qrCode'
])

@php
$opcao = fn (bool $marcada) => new \Illuminate\Support\HtmlString('<span class="opcao">' . ($marcada ? '&#9673;' : '&#9675;') . '</span>');
$campo = fn (string $valor) => new \Illuminate\Support\HtmlString('<span class="campo">' . (e($valor) ?: '&nbsp;') . '</span>');
$extra = fn (string $tipo, string $chave) => $requerimento->tipoRequisicao == $tipo ? ($requerimento->dadosExtra[$chave] ?? '') : '';
$tipoReintegracao = 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC';
@endphp

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
        }

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

        .situacao-column {
            width: 20%;
        }

        .botao-column {
            width: 5%;
            text-align: center;
        }

        .opcao {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 8px;
            margin: 0 2px;
        }

        .campo {
            display: inline-block;
            position: relative;
            top: 2px;
            min-width: 34px;
            height: 11px;
            line-height: 11px;
            margin: 0 2px;
            padding: 0 3px;
            border-radius: 6px;
            font-weight: normal;
            background-color: #e3ecc5;
            text-align: center;
        }

        .caixa {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000000;
            font-size: 9px;
            font-weight: bold;
            line-height: 9px;
            text-align: center;
        }

        .qr-column {
            width: 22%;
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
            padding-left: 6px;
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
            padding-left: 6px;
            text-align: initial;
            vertical-align: middle;
            line-height: 1.5;
            background-color: #e3ecc5;
        }

        .observacoes-column {
            width: auto;
            font-size: 16px;
            padding-left: 6px;
            text-align: initial;
            vertical-align: middle;
            line-height: 1.5;
            word-wrap: break-word;
            border: 1px solid #000000;
        }

        .botao-column,
        .iten-column,
        .anexo-column,
        .documentacao-column {
            padding-top: 4.25px;
            padding-bottom: 4.25px;
        }

        .bloco-titulo,
        .bloco-linha {
            padding-left: 10px;
            font-size: 8.5px;
        }

        .bloco-titulo {
            font-size: 11px;
        }

        .bloco-rotulo {
            width: 20%;
            border-right: none;
        }

        .bloco-valor {
            padding: 3px 4px 3px 2px;
            border-left: none;
        }

        .bloco-campo {
            padding: 0 6px;
            background-color: #e3ecc5;
            font-size: 8.5px;
            line-height: 12px;
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

    <table>
        <thead>
            <tr>
                <th class="campus-column">CAMPUS</th>
                <th class="nome-column">NOME DO(A) ALUNO(A)</th>
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

    <table>
        <thead>
            <tr>
                <th class="cpf-column">CPF</th>
                <th class="identidade-column">IDENTIDADE</th>
                <th class="expedidor-column">ORGÃO EXPED.</th>
                <th class="situacao-column">VÍNCULO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cpf-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->cpf }}</td>
                <td class="identidade-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->rg }}</td>
                <td class="expedidor-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->orgaoExpedidor }}</td>
                <td class="situacao-column observacoes-column-conteudo" style="text-transform: uppercase">{{ $requerimento->situacao }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="4" style="font-size: 14px; padding: 3px; text-align: left;">Marque a sua opção desejada abaixo</th>
            </tr>
            <tr>
                <th class="botao-column"><span class="caixa">X</span></th>
                <th class="iten">ITENS</th>
                <th class="anexo-column">ANEXOS -------></th>
                <th class="documentacao">DOCUMENTAÇÃO EXIGIDA (ANEXOS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Admissão por Transferência e Análise Curricular') }}</td>
                <td class="iten-column">Admissão por Transferência e Análise Curricular (anexos) - Solicitação no Protocolo Geral</td>
                <td class="anexo-column">c,f,g,h,i</td>
                <td class="documentacao-column">a - Atestado Médico</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Ajuste de Matrícula Semestral') }}</td>
                <td class="iten-column">Ajuste de Matrícula Semestral</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">b - Cópia da CTPS - Identificação e Contrato</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Autorização para cursar disciplinas em outras Instituições de Ensino Superior') }}</td>
                <td class="iten-column">Autorização para cursar disciplinas em outras Instituições de Ensino Superior (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">c - Declaração de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Cancelamento de Matrícula') }}</td>
                <td class="iten-column">Cancelamento de Matrícula</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">d - Declaração da Empresa com o respectivo horário</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Cancelamento de Disciplina') }}</td>
                <td class="iten-column">Cancelamento de Disciplina (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">e - Guia de Transferência</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Certificado de Conclusão') }}</td>
                <td class="iten-column">
                    Certificado de Conclusão - Ano ({{ $campo($extra('Certificado de Conclusão', 'ano')) }}) Semestre ({{ $campo($extra('Certificado de Conclusão', 'semestre')) }})
                </td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">f - Histórico Escolar do Ensino Fundamental (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Certidão - Autenticidade') }}</td>
                <td class="iten-column">Certidão - Autenticidade (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">g - Histórico Escolar do Ensino Médio (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Complementação de Matrícula') }}</td>
                <td class="iten-column">Complementação de Matrícula (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">h - Histórico Escolar do Ensino Superior (original)</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Cópia Xerox de Documento') }}</td>
                <td class="iten-column">Cópia Xerox de Documento (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column">i - Ementas das disciplinas cursadas com Aprovação</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Declaração de Colação de Grau e Tramitação de Diploma') }}</td>
                <td class="iten-column">Declaração de Colação de Grau e Tramitação de Diploma (curso tecnológico)</td>
                <td class="anexo-column">a/b, d</td>
                <td class="documentacao-column">j - Declaração de Unidade Militar</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Declaração de Matrícula ou Matrícula Vínculo') }}</td>
                <td class="iten-column">Declaração de Matrícula ou Matrícula Vínculo (especifique)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Declaração de Monitoria') }}</td>
                <td class="iten-column">Declaração de Monitoria</td>
                <td class="anexo-column"></td>
                <th class="documentacao-column" rowspan="3" class="observacoes-column"><strong>OBSERVAÇÕES:</strong></th>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Declaração para Estágio') }}</td>
                <td class="iten-column">
                    Declaração para Estágio - Conclusão Ano ({{ $campo($extra('Declaração para Estágio', 'ano')) }}) Semestre ({{ $campo($extra('Declaração para Estágio', 'semestre')) }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Diploma 1ªvia/2ªvia') }}</td>
                <td class="iten-column">
                    Diploma 1a Via ({{ $opcao($extra('Diploma 1ªvia/2ªvia', 'via') == '1ª via') }}) 2a ({{ $opcao($extra('Diploma 1ªvia/2ªvia', 'via') == '2ª via') }}) - Conclusão Ano ({{ $campo($extra('Diploma 1ªvia/2ªvia', 'ano')) }}) Semestre ({{ $campo($extra('Diploma 1ªvia/2ªvia', 'semestre')) }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Dispensa da prática de Educação Física') }}</td>
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
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Declaração Tramitação de Diploma') }}</td>
                <td class="iten-column">Declaração Tramitação de Diploma (técnico)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Ementa de disciplina') }}</td>
                <td class="iten-column">Ementa de disciplina - (especifique)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Guia de Transferência') }}</td>
                <td class="iten-column">Guia de Transferência</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Histórico Escolar') }}</td>
                <td class="iten-column">
                    Histórico Escolar - Ano ({{ $campo($extra('Histórico Escolar', 'ano')) }}) Semestre ({{ $campo($extra('Histórico Escolar', 'semestre')) }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Isenção de disciplinas cursadas') }}</td>
                <td class="iten-column">Isenção de disciplinas cursadas (anexo)</td>
                <td class="anexo-column">f/g/h,i</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Justificativa de falta(s) ou prova 2º chamada') }}</td>
                <td class="iten-column">Justificativa de falta(s) ou prova 2 chamada (anexos)</td>
                <td class="anexo-column">a,d,i</td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Matriz curricular') }}</td>
                <td class="iten-column">Matriz curricular</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Reabertura de Matrícula') }}</td>
                <td class="iten-column">Reabertura de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC') }}</td>
                <td class="iten-column">
                    Reintegração ({{ $opcao($extra($tipoReintegracao, 'opcao_reintegracao') == 'Reintegração') }})
                    Estágio ({{ $opcao($extra($tipoReintegracao, 'opcao_reintegracao') == 'Estágio') }})
                    Entrega do Relatório de Estágio ({{ $opcao($extra($tipoReintegracao, 'opcao_reintegracao') == 'Entrega do Relatório de Estágio') }})
                    TCC ({{ $opcao($extra($tipoReintegracao, 'opcao_reintegracao') == 'TCC') }})
                </td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Reintegração para Cursar') }}</td>
                <td class="iten-column">Reintegração para Cursar (Solicitar no Protocolo Geral)</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Solicitação de Conselho de Classe') }}</td>
                <td class="iten-column">Solicitação de Conselho de Classe</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Trancamento de Matrícula') }}</td>
                <td class="iten-column">Trancamento de Matrícula</td>
                <td class="anexo-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Transferência de Turno') }}</td>
                <td class="iten-column">Transferência de Turno (especifique turno)</td>
                <td class="anexo-column">a/j</td>
            </tr>
        </tbody>
    </table>

    @foreach (['LANÇAMENTO DE NOTA:' => 'Lançamento de Nota', 'REVISÃO DE NOTAS' => 'Revisão de Notas', 'REVISÃO DE FALTAS' => 'Revisão de Faltas'] as $titulo => $tipo)
    <table>
        <thead>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column bloco-titulo">{{ $titulo }}</th>
                <th class="documentacao-column bloco-linha bloco-rotulo">Nome do componente curricular:</th>
                <td class="bloco-valor"><div class="bloco-campo">{{ $extra($tipo, 'componente_curricular') }}&nbsp;</div></td>
            </tr>
            <tr>
                <th class="botao-column">{{ $opcao($requerimento->tipoRequisicao == $tipo) }}</th>
                <th class="iten-column bloco-linha">
                    @foreach (['1ª unidade', '2ª unidade'] as $unidade)
                    ({{ $opcao($extra($tipo, 'unidade') == $unidade) }}) {{ $unidade }}
                    @endforeach
                </th>
                <th class="documentacao-column bloco-linha bloco-rotulo">Nome do professor:</th>
                <td class="bloco-valor"><div class="bloco-campo">{{ $extra($tipo, 'nome_professor') }}&nbsp;</div></td>
            </tr>
            <tr>
                <th class="botao-column"></th>
                <th class="iten-column bloco-linha">
                    @foreach (['3ª unidade', '4ª unidade', 'Exame Final'] as $unidade)
                    ({{ $opcao($extra($tipo, 'unidade') == $unidade) }}) {{ $unidade }}
                    @endforeach
                </th>
                <th class="documentacao-column bloco-linha bloco-rotulo">Ano / Semestre:</th>
                <td class="bloco-valor"><div class="bloco-campo">{{ $extra($tipo, 'ano_semestre') }}&nbsp;</div></td>
            </tr>
        </thead>
    </table>
    @endforeach

    <!-- Últimas opções, depois dos blocos de nota e faltas -->
    <table>
        <tbody>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Tempo de escolaridade') }}</td>
                <td class="iten-column">Tempo de escolaridade</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
            <tr>
                <td class="botao-column">{{ $opcao($requerimento->tipoRequisicao == 'Outros') }}</td>
                <td class="iten-column">Outros (relatar em OBSERVAÇÕES)</td>
                <td class="anexo-column"></td>
                <td class="documentacao-column"></td>
            </tr>
        </tbody>
    </table>

    <table style="page-break-before: always;">
        <thead>
            <tr>
                <th>ASSINATURA DIGITAL</th>
                <th class="qr-column">VERIFICAR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="observacoes-column-conteudo">
                    {{ $requerimento->key }}<br>
                    Documento emitido automaticamente pelo sistema.<br>
                    Para conferir, leia o QR code ou acesse {{ $urlVerificacao }}
                </td>
                <td class="qr-column"><img src="{{ $qrCode }}" alt="QR code de verificação" style="width: 90px; height: 90px;"></td>
            </tr>
        </tbody>
    </table>

</body>

</html>