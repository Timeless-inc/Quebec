@php
    $requestIdentifier = $applicationRequest->key ?? $applicationRequest->id ?? 'N/A';
    $tipoRequisicao = is_object($applicationRequest->tipoRequisicao)
        ? ($applicationRequest->tipoRequisicao->nome ?? 'Não especificado')
        : ($applicationRequest->tipoRequisicao ?? 'Não especificado');
    $createdAt = isset($applicationRequest->created_at) ? \Carbon\Carbon::parse($applicationRequest->created_at)->format('d/m/Y') : now()->format('d/m/Y');
    $encaminhadoEm = isset($forwarding->created_at) ? \Carbon\Carbon::parse($forwarding->created_at)->format('d/m/Y \à\s H:i') : now()->format('d/m/Y \à\s H:i');
    $senderName = $forwarding->sender->name ?? 'Sistema';
    $senderRole = $forwarding->sender->role ?? null;
    $detailsUrl = isset($applicationRequest->id) ? url('/requerimentos/' . $applicationRequest->id) : url('/requerimentos');
@endphp
<x-emails.layout title="Requerimento encaminhado para análise" heading="Uma solicitação aguarda sua análise." eyebrow="Requerimento encaminhado" :preheader="'O requerimento #' . $requestIdentifier . ' foi encaminhado para sua análise no SRE.'" :action-url="$detailsUrl" action-label="Analisar requerimento" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $recipient->name }}</strong>.</p>
    <p style="margin: 0;">Você recebeu um requerimento para análise. Confira as informações e acesse o sistema para dar continuidade ao atendimento.</p>

    <x-emails.panel title="Resumo do requerimento">
        <p style="margin: 0 0 14px; color: #263e31; font-size: 19px; line-height: 27px; font-weight: 700;">{{ $tipoRequisicao }}</p>
        <x-emails.badge status="encaminhado" />
        <div style="height: 14px; line-height: 14px; font-size: 1px;">&nbsp;</div>
        <x-emails.details :items="[
            'Protocolo' => '#' . $requestIdentifier,
            'Solicitante' => $applicationRequest->nomeCompleto ?? 'Estudante',
            'E-mail' => $applicationRequest->email,
            'CPF' => $applicationRequest->cpf,
            'Campus' => $applicationRequest->campus,
            'Curso' => $applicationRequest->curso,
            'Período' => $applicationRequest->periodo,
            'Turno' => $applicationRequest->turno,
            'Solicitado em' => $createdAt,
        ]" />
    </x-emails.panel>
    @if($applicationRequest->motivo || $applicationRequest->observacoes)
        <p style="margin: 0 0 6px; color: #263e31; font-size: 13px; font-weight: 700;">Motivo / observações</p>
        <p style="margin: 0 0 22px; font-size: 14px; line-height: 23px; white-space: pre-line;">{{ $applicationRequest->motivo ?: $applicationRequest->observacoes }}</p>
    @endif
    <p style="margin: 0; color: #687b6d; font-size: 12px; line-height: 20px;">Encaminhado por <strong>{{ $senderName }}</strong>@if($senderRole) ({{ $senderRole }})@endif<br>{{ $encaminhadoEm }}</p>
</x-emails.layout>
