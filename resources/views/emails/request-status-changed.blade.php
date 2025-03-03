<!DOCTYPE html>
<html>
<head>
    <title>Status da Requisição Atualizado</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #3490dc; color: white; padding: 15px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .status-changed { background-color: #f8fafc; padding: 10px; border-left: 4px solid #3490dc; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; color: #718096; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Status Atualizado</h1>
        </div>
        <div class="content">
            <h2>Requisição #{{ $request->key }}</h2>
            <p>O status da sua requisição foi atualizado!</p>
            
            <div class="status-changed">
                <p><strong>Status anterior:</strong> {{ $oldStatus }}</p>
                <p><strong>Novo status:</strong> {{ $newStatus }}</p>
            </div>
            
            <p>Detalhes da requisição:</p>
            <ul>
                <li><strong>Tipo:</strong> {{ $request->tipoRequisicao }}</li>
                <li><strong>Nome:</strong> {{ $request->nomeCompleto }}</li>
                <li><strong>Data da criação:</strong> {{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i') }}</li>
                <li><strong>Data da atualização:</strong> {{ \Carbon\Carbon::parse($request->updated_at)->format('d/m/Y H:i') }}</li>
            </ul>
            
            <p>Para mais detalhes, acesse o sistema.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimentos Quebec - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>