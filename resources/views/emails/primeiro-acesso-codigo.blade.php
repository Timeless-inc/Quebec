<x-emails.layout title="Seu código de primeiro acesso" heading="Falta só confirmar seu e-mail." eyebrow="Ativação da conta" preheader="Use seu código de verificação para confirmar o e-mail e ativar sua conta no SRE." :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $user->name }}</strong>.</p>
    <p style="margin: 0;">Para ativar seu acesso ao SRE, informe o código abaixo na tela de confirmação do sistema.</p>

    <x-emails.panel title="Seu código de verificação">
        <p style="margin: 8px 0 14px; text-align: center; color: #164c35; font-family: 'Courier New', Courier, monospace; font-size: 36px; line-height: 46px; font-weight: 700; letter-spacing: 7px;">{{ $codigo }}</p>
        <p style="margin: 0; text-align: center; color: #687b6d; font-size: 12px; line-height: 20px;">Válido por <strong>{{ $minutos }} minutos</strong> · Uso único</p>
    </x-emails.panel>
    <p style="margin: 0 0 12px;">Não compartilhe este código com outras pessoas.</p>
    <p style="margin: 0; color: #687b6d; font-size: 13px; line-height: 22px;">Se você não tentou acessar o SRE, ignore esta mensagem. Nenhuma conta será ativada sem a confirmação do código.</p>
</x-emails.layout>
