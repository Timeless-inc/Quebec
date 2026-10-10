<x-emails.layout title="Boas-vindas ao SRE" heading="Sua vida acadêmica, mais simples." eyebrow="Boas-vindas ao SRE" preheader="Sua conta está pronta. Abra solicitações e acompanhe seus requerimentos em um só lugar." :action-url="url('/login')" action-label="Acessar minha conta" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $user->name }}</strong>.</p>
    <p style="margin: 0;">Boas-vindas ao Sistema de Requerimento do Estudante do IFPE. Sua conta está pronta para abrir e acompanhar solicitações acadêmicas.</p>

    <x-emails.panel title="Tudo em um só lugar">
        <p style="margin: 0 0 12px;"><strong style="color: #263e31;">01. Envie sua solicitação</strong><br>Escolha o tipo de requerimento e anexe os documentos necessários.</p>
        <p style="margin: 0 0 12px;"><strong style="color: #263e31;">02. Acompanhe o andamento</strong><br>Consulte o status e as movimentações pelo sistema.</p>
        <p style="margin: 0;"><strong style="color: #263e31;">03. Receba a resposta</strong><br>Veja as orientações e o resultado do seu pedido.</p>
    </x-emails.panel>
    <x-emails.details :items="['Nome' => $user->name, 'E-mail da conta' => $user->email]" />
</x-emails.layout>
