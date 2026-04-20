<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Expiração de Evento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 min-h-screen bg-gradient-to-br from-slate-100 via-emerald-50 to-slate-200 p-5 font-sans text-slate-700">
    @php
        $applicationController = app('App\Http\Controllers\ApplicationController');
        $tiposRequisicao = $applicationController->getTiposRequisicao();
        $tipoRequisicao = $tiposRequisicao[$event->requisition_type_id] ?? ('Tipo #' . $event->requisition_type_id);

        $startDate = \Carbon\Carbon::parse($event->start_date)->format('d/m/Y');
        $endDate = \Carbon\Carbon::parse($event->end_date)->format('d/m/Y');
        $detailsUrl = isset($event->id) ? url('/eventos/' . $event->id) : url('/eventos');

        if ($daysLeft <= 0) {
            $urgencyLabel = 'Expira Hoje';
            $urgencyClasses = 'text-red-700 bg-red-100 border border-red-200';
            $introText = 'Este é um aviso urgente: o evento expira hoje.';
        } elseif ($daysLeft === 1) {
            $urgencyLabel = 'Expira Amanhã';
            $urgencyClasses = 'text-amber-700 bg-amber-100 border border-amber-200';
            $introText = 'Este é um aviso importante: o evento expira amanhã.';
        } else {
            $urgencyLabel = 'Expira em ' . $daysLeft . ' dias';
            $urgencyClasses = 'text-blue-700 bg-blue-100 border border-blue-200';
            $introText = 'Este é um aviso preventivo para você se organizar antes do encerramento.';
        }
    @endphp

    <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-6 py-8 text-center text-white sm:px-10">
            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-md ring-4 ring-white/30">
                <img src="{{ asset('img/logo-sre.png') }}" alt="Logo SRE" class="h-10 w-10 object-contain">
            </div>
            <h1 class="text-2xl font-bold leading-tight sm:text-3xl">Aviso de Expiração de Evento</h1>
            <p class="mt-2 text-sm text-emerald-50">Atenção ao prazo de submissão.</p>
        </div>

        <div class="px-6 py-8 sm:px-10">
            <p class="mb-4 text-base leading-7 text-slate-700">Olá <span class="rounded bg-emerald-100 px-2 py-1 font-semibold text-emerald-700">{{ $user->name }}</span>,</p>
            <p class="mb-4 text-base leading-7 text-slate-700">{{ $introText }}</p>

            <div class="mb-4">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $urgencyClasses }}">{{ $urgencyLabel }}</span>
            </div>

            <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50/70 p-4">
                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Detalhes do evento</p>
                <p class="text-sm text-slate-700"><strong>Título:</strong> {{ $event->title }}</p>
                <p class="text-sm text-slate-700"><strong>Tipo de requisição:</strong> {{ $tipoRequisicao }}</p>
                <p class="text-sm text-slate-700"><strong>Início:</strong> {{ $startDate }}</p>
                <p class="text-sm text-slate-700"><strong>Término:</strong> {{ $endDate }}</p>
            </div>

            <p class="mb-6 text-base leading-7 text-slate-700">Tome as providências necessárias antes do encerramento para não perder o prazo.</p>

            <div class="text-center">
                <a href="{{ $detailsUrl }}" class="inline-block rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white no-underline hover:bg-emerald-500">Ver detalhes do evento</a>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center sm:px-10">
            <p class="text-xs leading-6 text-slate-500">© {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)</p>
            <p class="text-xs leading-6 text-slate-500">Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>