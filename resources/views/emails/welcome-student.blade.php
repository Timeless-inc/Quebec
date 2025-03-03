<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo ao Sistema de Requerimentos</title>
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
            <h1>Bem-vindo(a)!</h1>
        </div>
        <div class="content">
            <h2>Olá {{ $user->name }},</h2>
            <p>Sua conta no Sistema de Requerimentos Quebec foi criada com sucesso!</p>
            <p>Agora você pode acessar o sistema e enviar suas requisições sempre que necessário.</p>
            <p>Suas informações:</p>
            <ul>
                <li><strong>Nome:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Matrícula:</strong> {{ $user->matricula }}</li>
            </ul>
            <p>Se tiver qualquer dúvida, entre em contato com o suporte.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimentos Quebec - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>