<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo ao Sistema de Requerimentos</title>
    <style>
        /* Reset básico e fontes */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
        }

        .container { 
            max-width: 700px; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        .header { 
            background: linear-gradient(90deg, #10A11A 0%, #28c840 100%);
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: white;
            border-radius: 2px;
        }

        .header h1 { 
            font-size: 30px; 
            font-weight: 600;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .content { 
            padding: 35px; 
        }

        .welcome-message {
            font-size: 17px;
            line-height: 1.8;
            color: #444;
            margin-bottom: 20px;
        }

        .highlight { 
            color: #10A11A; 
            font-weight: 600;
            background: rgba(16, 161, 26, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .highlight:hover {
            background: rgba(16, 161, 26, 0.2);
        }

        .cta-button {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 30px;
            background: #10A11A;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: #28c840;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 161, 26, 0.3);
        }

        .footer { 
            padding: 20px 35px;
            background: #fafafa;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .footer p {
            color: #718096;
            font-size: 13px;
            line-height: 1.6;
        }

        .welcome-icon {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            line-height: 50px;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome-icon">🎉</div>
            <h1>Bem-vindo(a) ao Sistema de Requerimento do Estudante</h1>
        </div>
        <div class="content">
            <p class="welcome-message">Olá <span class="highlight">{{ $user->name }}</span>,</p>
            <p class="welcome-message">Estamos muito felizes em tê-lo(a) conosco!</p>
            <p class="welcome-message">A partir de agora, você pode enviar seus requerimentos de forma rápida e prática, diretamente pelo nosso sistema. Estamos aqui para facilitar sua vida acadêmica e garantir que tudo corra da melhor forma possível.</p>
            <p class="welcome-message">Se precisar de ajuda ou tiver alguma dúvida, nossa equipe de suporte está à disposição para ajudar. Não hesite em entrar em contato!</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimento do Estudante - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>