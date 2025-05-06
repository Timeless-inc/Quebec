<!DOCTYPE html>
<html>
<head>
    <title>Nova Requisição Criada</title>
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

        .content { 
            padding: 30px; 
        }

        .content p {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
            margin-bottom: 15px;
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

        .success-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            line-height: 40px;
            text-align: center;
            font-size: 20px;
            margin-bottom: 15px;
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
            <div class="success-icon">✓</div>
            <h1>NOVA REQUISIÇÃO</h1>
        </div>
        <div class="content">
            <p>Olá, <span class="highlight">{{ $user->name ?? 'Aluno' }}</span>!</p>
            <p>Sua solicitação de <span class="highlight">{{
                is_string($request->tipoRequisicao) ? 
                $request->tipoRequisicao : 
                (is_object($request->tipoRequisicao) && isset($request->tipoRequisicao->nome) ? 
                    $request->tipoRequisicao->nome : 
                    'Não especificado'
                )
            }}</span> foi registrada com sucesso!</p>
            <p>Aqui está o número da sua requisição: <span class="highlight">#{{ $request->key ?? $request->id ?? 'N/A' }}</span>, feito no dia <span class="highlight">{{
                isset($request->created_at) ? 
                \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i') : 
                date('d/m/Y H:i')
            }}</span>.</p>
            <p>Você receberá notificações por email conforme o status da sua requisição for atualizado.</p>
            <a href="#" class="action-button">Ver Detalhes</a>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Sistema de Requerimento do Estudante - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>