<!DOCTYPE html>
<html>
<head>
    <title>Novo Requerimento para Análise</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; background-color: #f7fafc; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; display: flex; align-items: center; justify-content: center; padding: 20px; min-height: 100vh; }
        .container { max-width: 700px; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07); overflow: hidden; border-top: 4px solid #10A11A; } /* Verde Principal */
        .header { background: #f1f5f9; color: #1e293b; padding: 30px; text-align: center; border-bottom: 1px solid #e2e8f0; }
        .header h1 { font-size: 28px; font-weight: 600; line-height: 1.3; }
        .content { padding: 35px; color: #334155; }
        .message-text { font-size: 16px; line-height: 1.7; margin-bottom: 25px; }
        .highlight { color: #10A11A; font-weight: 600; } /* Verde Principal */
        .details-box { background: #f8fafc; border-left: 4px solid #10A11A; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0; } /* Verde Principal */
        .details-box h3 { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 15px; }
        .details-box p { font-size: 15px; line-height: 1.8; color: #475569; margin-bottom: 8px; }
        .details-box p strong { color: #334155; min-width: 150px; display: inline-block; }
        .cta-button { display: inline-block; margin: 20px auto; padding: 14px 35px; background-color: #10A11A; color: white; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 500; transition: all 0.3s ease; } /* Verde Principal */
        .cta-button:hover { background-color: #28c840; transform: translateY(-2px); } /* Verde Hover */
        .button-wrapper { text-align: center; }
        .footer { padding: 25px 35px; background: #f8fafc; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { color: #64748b; font-size: 13px; line-height: 1.6; }
        .notification-icon { display: inline-block; width: 50px; height: 50px; background-color: rgba(16, 161, 26, 0.1); color: #10A11A; border-radius: 50%; line-height: 50px; text-align: center; font-size: 24px; margin-bottom: 15px; } /* Verde Principal */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Novo Requerimento para Análise</h1>
        </div>
        <div class="content">
            <p class="message-text">Olá, <span class="highlight">{{ $recipient->name }}</span>,</p>
            <p class="message-text">Um novo requerimento foi encaminhado para sua análise e parecer.</p>

            <div class="details-box">
                <h3>Detalhes do Requerimento</h3>
                <p><strong>Protocolo:</strong> {{ $applicationRequest->id }}</p>
                <p><strong>Tipo:</strong> {{ $applicationRequest->tipoRequisicao }}</p>
                <p><strong>Data da Solicitação:</strong> {{ $applicationRequest->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <p class="message-text">Por favor, acesse o painel para revisar os detalhes completos e registrar seu parecer.</p>
            
            <div class="button-wrapper">
                <a href="{{ route('dashboard') }}" class="cta-button">Ver Requerimento no Painel</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimento do Estudante - Todos os direitos reservados</p>
            <p>Este é um e-mail automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>