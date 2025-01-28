<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requerimento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin: 5px 0;
        }
        .signature {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Requerimento</h1>
        <p>Documento Oficial</p>
    </div>

    <div class="content">
        <p><strong>Nome:</strong> {{ $requerimento->nome }}</p>
        <p><strong>CPF:</strong> {{ $requerimento->cpf }}</p>
        <p><strong>E-mail:</strong> {{ $requerimento->email }}</p>
        <p><strong>Descrição:</strong> {{ $requerimento->descricao }}</p>
    </div>

    <div class="signature">
        <p><strong>Assinatura Digital:</strong> {{ $assinatura }}</p>
        <p>Este documento foi autenticado automaticamente e é válido para comprovação.</p>
    </div>
</body>
</html>
