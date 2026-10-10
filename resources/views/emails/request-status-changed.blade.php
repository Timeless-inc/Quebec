@php
    $requestIdentifier = $request->key ?? $request->id ?? 'N/A';
    $tipoRequisicao = is_object($request->tipoRequisicao)
        ? ($request->tipoRequisicao->nome ?? 'Não especificado')
        : ($request->tipoRequisicao ?? 'Não especificado');
    $updatedAt = isset($request->updated_at) ? \Carbon\Carbon::parse($request->updated_at)->format('d/m/Y \à\s H:i') : now()->format('d/m/Y \à\s H:i');
    $detailsUrl = isset($request->id) ? url('/requerimentos/' . $request->id) : url('/requerimentos');
@endphp
<x-emails.layout title="Atualização do seu requerimento" heading="Seu requerimento tem uma atualização." eyebrow="Acompanhamento do requerimento" :preheader="'O status da sua solicitação mudou para ' . $newStatus . '. Confira os detalhes no SRE.'" :action-url="$detailsUrl" action-label="Acompanhar requerimento" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $request->nomeCompleto ?? 'estudante' }}</strong>.</p>
    <p style="margin: 0;">Houve uma atualização na sua solicitação de <strong style="color: #263e31;">{{ $tipoRequisicao }}</strong>. Confira o novo status abaixo.</p>

    <x-emails.panel title="O que mudou">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
            <tr>
                <td width="50%" valign="top" style="padding: 0 12px 0 0; border-right: 1px solid #dce5df;">
                    <p style="margin: 0 0 10px; color: #687b6d; font-size: 12px; line-height: 18px;">Status anterior</p>
                    <x-emails.badge :status="$oldStatus" />
                </td>
                <td width="50%" valign="top" style="padding: 0 0 0 18px;">
                    <p style="margin: 0 0 10px; color: #687b6d; font-size: 12px; line-height: 18px;">Status atual</p>
                    <x-emails.badge :status="$newStatus" />
                </td>
            </tr>
        </table>
    </x-emails.panel>
    <x-emails.details :items="['Protocolo' => '#' . $requestIdentifier, 'Atualizado em' => $updatedAt]" />
    <p style="margin: 22px 0 0;">Acesse o SRE para consultar a resposta do setor responsável e os próximos passos.</p>
</x-emails.layout>
