@php
    $tiposRequisicao = app(\App\Http\Controllers\ApplicationController::class)->getTiposRequisicao();
    $tipoRequisicao = $tiposRequisicao[$event->requisition_type_id] ?? ('Tipo #' . $event->requisition_type_id);
    $startDate = \Carbon\Carbon::parse($event->start_date)->format('d/m/Y');
    $endDate = \Carbon\Carbon::parse($event->end_date)->format('d/m/Y');
    $urgencyLabel = $daysLeft <= 0 ? 'O prazo termina hoje.' : ($daysLeft === 1 ? 'O prazo termina amanhã.' : 'Faltam ' . $daysLeft . ' dias para o encerramento.');
@endphp
<x-emails.layout title="Lembrete de prazo acadêmico" :heading="$urgencyLabel" eyebrow="Atenção ao prazo" :preheader="$event->title . ' — ' . $urgencyLabel" :action-url="route('dashboard')" action-label="Consultar prazo no SRE" :message="$message ?? null">
    <p style="margin: 0 0 16px;">Olá, <strong style="color: #263e31;">{{ $user->name }}</strong>.</p>
    <p style="margin: 0;">O período de solicitações deste evento está chegando ao fim. Se você precisa enviar um requerimento, confira os detalhes e não deixe para a última hora.</p>

    <x-emails.panel title="Prazo para solicitações" tone="warning">
        <p style="margin: 0 0 8px; color: #74551e; font-size: 14px; line-height: 22px; font-weight: 700;">{{ $urgencyLabel }}</p>
        <p style="margin: 0; color: #263e31; font-size: 20px; line-height: 28px; font-weight: 700;">{{ $event->title }}</p>
    </x-emails.panel>
    <x-emails.details :items="['Solicitação' => $tipoRequisicao, 'Início' => $startDate, 'Encerramento' => $endDate]" />
    <p style="margin: 22px 0 0;">Acesse o sistema para consultar as orientações e enviar sua solicitação dentro do período previsto.</p>
</x-emails.layout>
