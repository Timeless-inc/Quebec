<!DOCTYPE html>
<html>
<head>
    <title>Status da Requisição Atualizado</title>
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
            max-width: 650px; 
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
            padding: 30px; 
            text-align: center; 
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: white;
            border-radius: 2px;
        }

        .header h1 { 
            font-size: 28px; 
            font-weight: 600;
            letter-spacing: 1px;
        }

        .header h2 { 
            font-size: 18px; 
            font-weight: 400;
            opacity: 0.9;
            margin-top: 8px;
        }

        .content { 
            padding: 30px; 
        }

        .subtitle {
            color: #666;
            font-size: 15px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .content p {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
        }

        .status-box { 
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 8px;
            border-left: 5px solid #10A11A;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .status-box:hover {
            background: #f0fdf4;
            transform: translateX(5px);
        }

        .status-box p { 
            color: #333;
            font-size: 15px;
        }

        .highlight { 
            color: #10A11A; 
            font-weight: 600;
            background: rgba(16, 161, 26, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
        }

        .footer { 
            padding: 20px 30px;
            background: #fafafa;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .footer p {
            color: #718096;
            font-size: 13px;
            line-height: 1.6;
        }

        .action-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #10A11A;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            background: #28c840;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 161, 26, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STATUS ATUALIZADO</h1>
            <h2>Requisição #{{ $request->key }}</h2>
        </div>
        <div class="content">
            <p class="subtitle">Olá {{ $request->nomeCompleto }}!</p>
            <p>Sua solicitação de <span class="highlight">{{ $request->tipoRequisicao }}</span>, feita no dia <span class="highlight">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</span>, 
            {{ $newStatus == 'pendente' ? 'está' : 'foi' }} <span class="highlight">{{ $newStatus }}</span>.</p>
            <p>Requerimento atualizado em <span class="highlight">{{ \Carbon\Carbon::parse($request->updated_at)->format('d/m/Y H:i') }}</span>.</p>
            <p>Para mais detalhes, acesse o sistema!</p>
            <a href="#" class="action-button">Acessar Sistema</a>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimento do Estudante - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>