<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redefinição de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            background-color: #10A11A;
            margin: -30px -30px 20px;
            border-radius: 8px 8px 0 0;
            padding: 30px;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: white;
        }
        .content {
            margin-bottom: 30px;
            padding: 0 20px;
        }
        .button {
            display: inline-block;
            background-color: #10A11A;
            color: white !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .help-text {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SRE - Redefinição de Senha</h1>
        </div>
        
        <div class="content">
            <p>Olá,</p>
            <p>Recebemos uma solicitação para redefinir a senha da sua conta no SRE.</p>
            <p>Para redefinir sua senha, clique no botão abaixo:</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Redefinir Senha</a>
            </div>
            
            <p>Este link de redefinição de senha expirará em 60 minutos.</p>
            <p>Se você não solicitou a redefinição de senha, nenhuma ação é necessária.</p>
            
            <div class="help-text">
                <p>Se você estiver com problemas para clicar no botão "Redefinir Senha", copie e cole a URL abaixo no seu navegador:</p>
                <p><a href="{{ $url }}">{{ $url }}</a></p>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} SRE - Sistema de Requerimento do Estudante</p>
        </div>
    </div>
</body>
</html>