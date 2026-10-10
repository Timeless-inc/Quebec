@php
    $expiresIn = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);
@endphp
<x-emails.layout title="Redefinir sua senha" heading="Vamos recuperar seu acesso." eyebrow="Segurança da conta" preheader="Recebemos seu pedido de redefinição de senha. Use o link para criar uma nova senha." :action-url="$url" action-label="Criar uma nova senha" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá,</p>
    <p style="margin: 0;">Recebemos uma solicitação para redefinir a senha da sua conta no SRE. Clique no botão abaixo para escolher uma nova senha.</p>

    <x-emails.panel title="Para sua segurança">
        <p style="margin: 0 0 10px;">Este link é válido por <strong style="color: #263e31;">{{ $expiresIn }} minutos</strong>.</p>
        <p style="margin: 0;">Se você não solicitou a alteração, ignore este e-mail. Sua senha continuará a mesma.</p>
    </x-emails.panel>
    <x-slot:afterAction>
        <p style="margin: 28px 0 8px; color: #687b6d; font-size: 12px; line-height: 20px;">Se o botão não funcionar, copie e cole este endereço no navegador:</p>
        <p style="margin: 0; font-size: 12px; line-height: 20px; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-all;"><a href="{{ $url }}" style="color: #196b43; text-decoration: underline; word-break: break-all;">{{ $url }}</a></p>
    </x-slot:afterAction>
</x-emails.layout>
