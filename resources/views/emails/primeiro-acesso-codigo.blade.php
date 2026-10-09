<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de primeiro acesso</title>
</head>
{{-- Estilos embutidos: clientes de e-mail ignoram folhas de estilo e scripts externos --}}
<body style="margin: 0; padding: 24px 12px; background-color: #f1f5f9; font-family: Arial, Helvetica, sans-serif; color: #334155;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="padding: 28px 32px; background-color: #047857; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 20px; line-height: 1.3;">Sistema de Requerimento do Estudante</h1>
                <p style="margin: 6px 0 0; font-size: 14px; color: #d1fae5;">Código de primeiro acesso</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6;">Olá, <strong>{{ $user->name }}</strong>.</p>
                <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6;">Use o código abaixo para confirmar seu e-mail e ativar seu acesso ao SRE:</p>

                <p style="margin: 0 0 24px; padding: 18px; text-align: center; font-size: 34px; font-weight: bold; letter-spacing: 10px; color: #065f46; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px;">{{ $codigo }}</p>

                <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.6;">O código vale por {{ $minutos }} minutos e só pode ser usado uma vez.</p>
                <p style="margin: 0; font-size: 14px; line-height: 1.6;">Se você não tentou acessar o sistema, ignore este e-mail: nenhuma conta será ativada sem o código.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 18px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center; font-size: 12px; line-height: 1.6; color: #64748b;">
                © {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)<br>
                Este e-mail foi enviado automaticamente. Por favor, não responda.
            </td>
        </tr>
    </table>
</body>
</html>
