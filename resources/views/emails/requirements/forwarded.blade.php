<!DOCTYPE html>
<html>
<head>
    <title>Novo Requerimento Encaminhado para Análise</title>
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
            background: linear-gradient(90deg, #0066cc 0%, #0052a3 100%);
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

        .info-box {
            background: #f0f4f8;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box strong {
            color: #0066cc;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .detail:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
        }

        .detail-value {
            color: #777;
            text-align: right;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(90deg, #0066cc 0%, #0052a3 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .action-button:hover {
            transform: scale(1.05);
        }

        .highlight { 
            color: #0066cc; 
            font-weight: 600;
            background: rgba(0, 102, 204, 0.1);
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

        .status-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #0066cc;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Novo Requerimento para Análise</h1>
        </div>

        <div class="content">
            <p>Olá <strong>{{ $recipient->name }}</strong>,</p>

            <p>Um novo requerimento foi encaminhado para sua análise e aprovação. Por favor, revise os detalhes abaixo:</p>

            <div class="info-box">
                <strong>Informações do Requerimento:</strong>
                
                <div class="detail">
                    <span class="detail-label">ID do Requerimento:</span>
                    <span class="detail-value">{{ $applicationRequest->key ?? $applicationRequest->id }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">Nome do Solicitante:</span>
                    <span class="detail-value">{{ $applicationRequest->nomeCompleto }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $applicationRequest->email }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">CPF:</span>
                    <span class="detail-value">{{ $applicationRequest->cpf }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">Tipo de Requisição:</span>
                    <span class="detail-value">{{ $applicationRequest->tipoRequisicao }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">Campus:</span>
                    <span class="detail-value">{{ $applicationRequest->campus }}</span>
                </div>

                <div class="detail">
                    <span class="detail-label">Status Atual:</span>
                    <span class="detail-value">
                        <span class="status-badge">{{ ucfirst(str_replace('_', ' ', $applicationRequest->status)) }}</span>
                    </span>
                </div>

                @if($applicationRequest->observacoes)
                <div class="detail">
                    <span class="detail-label">Observações:</span>
                    <span class="detail-value" style="text-align: left; display: block; margin-top: 5px;">
                        {{ $applicationRequest->observacoes }}
                    </span>
                </div>
                @endif
            </div>

            <p>Por gentileza, acesse o sistema para revisar e processar este requerimento.</p>

            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="action-button">Acessar Sistema</a>
            </p>

            <p style="font-size: 14px; color: #999; margin-top: 20px;">
                <em>Encaminhado por: {{ $forwarding->sender->name ?? 'Sistema' }} ({{ $forwarding->sender->role ?? 'N/A' }})</em><br>
                <em>Data: {{ now()->format('d/m/Y H:i') }}</em>
            </p>
        </div>

        <div class="footer">
            <p>
                Este é um email automático do sistema de gerenciamento de requerimentos. 
                Não responda este email.
            </p>
            <p>Se você recebeu este email por engano, por favor ignore.</p>
        </div>
    </div>
</body>
</html>
