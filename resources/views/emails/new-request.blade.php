<!DOCTYPE html>
<html>
<head>
    <title>Nova Requisição Criada</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #3490dc; color: white; padding: 15px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; color: #718096; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nova Requisição</h1>
        </div>
        <div class="content">
            <h2>Olá {{ $user->name ?? 'Aluno' }},</h2>
            <p>Sua requisição foi registrada com sucesso!</p>
            <p>Detalhes da requisição:</p>
            <ul>
                <li><strong>Número:</strong> #{{ $request->key ?? $request->id ?? 'N/A' }}</li>
                <li><strong>Tipo:</strong> {{ 
                    is_string($request->tipoRequisicao) ? 
                    $request->tipoRequisicao : 
                    (is_object($request->tipoRequisicao) && isset($request->tipoRequisicao->nome) ? 
                        $request->tipoRequisicao->nome : 
                        'Não especificado') 
                }}</li>
                <li><strong>Data:</strong> {{ 
                    isset($request->created_at) ? 
                    \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i') : 
                    date('d/m/Y H:i') 
                }}</li>
                <li><strong>Status:</strong> {{ $request->status ?? $request->situacao ?? 'Pendente' }}</li>
            </ul>
            <p>Você receberá notificações por email conforme o status da sua requisição for atualizado.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimentos Quebec - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>