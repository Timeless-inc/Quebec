@php
    $tiposRequisicao = app(\App\Http\Controllers\ApplicationController::class)->getTiposRequisicao();
    $tipoRequisicao = $tiposRequisicao[$event->requisition_type_id] ?? ('Tipo #' . $event->requisition_type_id);
    $startDate = \Carbon\Carbon::parse($event->start_date)->format('d/m/Y');
    $endDate = \Carbon\Carbon::parse($event->end_date)->format('d/m/Y');
@endphp
<x-emails.layout title="Novo período de solicitações" heading="Um novo período de solicitações está aberto." eyebrow="Calendário acadêmico" :preheader="$event->title . ': confira as datas e envie sua solicitação dentro do prazo.'" :action-url="route('dashboard')" action-label="Consultar no SRE" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $user->name }}</strong>.</p>
    <p style="margin: 0;">Um novo evento acadêmico foi cadastrado no SRE. Confira o período e o tipo de solicitação disponível.</p>

    <x-emails.panel title="Detalhes do evento">
        <p style="margin: 0 0 16px; color: #263e31; font-size: 20px; line-height: 28px; font-weight: 700;">{{ $event->title }}</p>
        <x-emails.details :items="['Solicitação' => $tipoRequisicao, 'Início' => $startDate, 'Encerramento' => $endDate]" />
    </x-emails.panel>
    <p style="margin: 0;">Organize seus documentos e acompanhe o calendário para enviar seu requerimento dentro do prazo.</p>
</x-emails.layout>
