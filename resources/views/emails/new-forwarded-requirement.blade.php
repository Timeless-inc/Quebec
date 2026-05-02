<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Requerimento Encaminhado para Análise</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-slate-200 p-5 font-sans text-slate-700">
    @php
        $requestIdentifier = $applicationRequest->key ?? $applicationRequest->id ?? 'N/A';
        $solicitanteName = $applicationRequest->nomeCompleto ?? 'Aluno';
        $tipoRequisicao = $applicationRequest->tipoRequisicao ?? 'Não especificado';
        $createdAt = isset($applicationRequest->created_at)
            ? \Carbon\Carbon::parse($applicationRequest->created_at)->format('d/m/Y')
            : date('d/m/Y');
        $encaminhadoEm = isset($forwarding->created_at)
            ? \Carbon\Carbon::parse($forwarding->created_at)->format('d/m/Y H:i')
            : date('d/m/Y H:i');
        $senderName = $forwarding->sender->name ?? 'Sistema';
        $senderRole = $forwarding->sender->role ?? 'N/A';
        $detailsUrl = isset($applicationRequest->id)
            ? url('/requerimentos/' . $applicationRequest->id)
            : url('/requerimentos');
    @endphp

    <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-8 text-center text-white sm:px-10">
            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-md ring-4 ring-white/30">
                <img src="{{ asset('img/logo-sre.png') }}" alt="Logo SRE" class="h-10 w-10 object-contain">
            </div>
            <h1 class="text-2xl font-bold leading-tight sm:text-3xl">Requerimento Encaminhado</h1>
            <p class="mt-2 text-sm text-blue-50">Para análise e aprovação</p>
        </div>

        <div class="px-6 py-8 sm:px-10">
            <p class="mb-4 text-base leading-7 text-slate-700">Olá <span class="rounded bg-blue-100 px-2 py-1 font-semibold text-blue-700">{{ $recipient->name }}</span>,</p>
            <p class="mb-6 text-base leading-7 text-slate-700">Um novo requerimento foi encaminhado para sua análise e aprovação. Por favor, revise os detalhes abaixo:</p>

            <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50/70 p-5">
                <p class="mb-4 text-xs font-semibold uppercase tracking-wide text-blue-700">Informações do Requerimento</p>

                <div class="space-y-3">
                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">ID do Requerimento:</span>
                        <span class="text-slate-600">{{ $requestIdentifier }}</span>
                    </div>

                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Nome do Solicitante:</span>
                        <span class="text-slate-600">{{ $solicitanteName }}</span>
                    </div>

                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Email:</span>
                        <span class="text-slate-600">{{ $applicationRequest->email }}</span>
                    </div>

                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">CPF:</span>
                        <span class="text-slate-600">{{ $applicationRequest->cpf }}</span>
                    </div>

                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Tipo de Requisição:</span>
                        <span class="text-slate-600">{{ $tipoRequisicao }}</span>
                    </div>

                    @if($applicationRequest->campus)
                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Campus:</span>
                        <span class="text-slate-600">{{ $applicationRequest->campus }}</span>
                    </div>
                    @endif

                    @if($applicationRequest->curso)
                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Curso:</span>
                        <span class="text-slate-600">{{ $applicationRequest->curso }}</span>
                    </div>
                    @endif

                    @if($applicationRequest->periodo)
                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Período:</span>
                        <span class="text-slate-600">{{ $applicationRequest->periodo }}</span>
                    </div>
                    @endif

                    @if($applicationRequest->turno)
                    <div class="flex justify-between border-b border-blue-100 pb-2">
                        <span class="font-semibold text-slate-700">Turno:</span>
                        <span class="text-slate-600">{{ $applicationRequest->turno }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between pb-2">
                        <span class="font-semibold text-slate-700">Status:</span>
                        <span class="inline-flex rounded-full bg-blue-200 px-3 py-1 text-xs font-semibold text-blue-700">Encaminhado</span>
                    </div>

                    @if($applicationRequest->motivo || $applicationRequest->observacoes)
                    <div class="mt-4 rounded bg-slate-50 p-3">
                        <span class="block font-semibold text-slate-700 mb-1">Motivo/Observações:</span>
                        <span class="block text-slate-600">{{ $applicationRequest->motivo ?? $applicationRequest->observacoes }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-6 rounded-lg border-l-4 border-slate-400 bg-slate-50 p-4">
                <p class="text-sm text-slate-700">
                    <strong>Encaminhado por:</strong> {{ $senderName }} ({{ $senderRole }})<br>
                    <strong>Data:</strong> {{ $encaminhadoEm }}
                </p>
            </div>

            <p class="mb-6 text-base leading-7 text-slate-700">Acesse o sistema para revisar todos os detalhes e processar este requerimento.</p>

            <div class="text-center">
                <a href="{{ $detailsUrl }}" class="inline-block rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white no-underline hover:bg-blue-500">Acessar Sistema</a>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center sm:px-10">
            <p class="text-xs leading-6 text-slate-500">© {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)</p>
            <p class="text-xs leading-6 text-slate-500">Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>
