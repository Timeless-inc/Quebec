@php
    $tipoRequisicao = is_object($request->tipoRequisicao)
        ? ($request->tipoRequisicao->nome ?? 'Não especificado')
        : ($request->tipoRequisicao ?? 'Não especificado');
    $requestIdentifier = $request->key ?? $request->id ?? 'N/A';
    $createdAt = isset($request->created_at) ? \Carbon\Carbon::parse($request->created_at)->format('d/m/Y \à\s H:i') : now()->format('d/m/Y \à\s H:i');
    $detailsUrl = isset($request->id) ? url('/requerimentos/' . $request->id) : url('/requerimentos');
@endphp
<x-emails.layout title="Requerimento recebido" heading="Recebemos seu requerimento." eyebrow="Solicitação registrada" preheader="Sua solicitação foi registrada com sucesso. Acompanhe o andamento pelo SRE." :action-url="$detailsUrl" action-label="Ver meu requerimento" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $user->name ?? 'estudante' }}</strong>.</p>
    <p style="margin: 0;">Sua solicitação foi registrada com sucesso. Agora você pode acompanhar cada etapa diretamente no sistema.</p>

    <x-emails.panel title="Resumo da solicitação">
        <p style="margin: 0 0 14px; color: #263e31; font-size: 19px; line-height: 27px; font-weight: 700;">{{ $tipoRequisicao }}</p>
        <x-emails.badge :status="$request->status ?? 'em_andamento'" />
        <div style="height: 14px; line-height: 14px; font-size: 1px;">&nbsp;</div>
        <x-emails.details :items="['Protocolo' => '#' . $requestIdentifier, 'Enviado em' => $createdAt]" />
    </x-emails.panel>
    <p style="margin: 0;">Você receberá um e-mail quando houver uma mudança de status. Guarde o protocolo para consultar sua solicitação.</p>
</x-emails.layout>
