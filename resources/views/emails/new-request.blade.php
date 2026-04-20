<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Requisição Criada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 min-h-screen bg-gradient-to-br from-slate-100 via-emerald-50 to-slate-200 p-5 font-sans text-slate-700">
    @php
        $rawStatus = $request->status ?? 'em_andamento';

        $statusMap = [
            'em_andamento' => [
                'label' => 'Em Análise',
                'classes' => 'text-blue-700 bg-blue-100 border border-blue-200',
            ],
            'pendente' => [
                'label' => 'Pendente',
                'classes' => 'text-amber-700 bg-amber-100 border border-amber-200',
            ],
            'finalizado' => [
                'label' => 'Aprovado',
                'classes' => 'text-emerald-700 bg-emerald-100 border border-emerald-200',
            ],
            'deferido' => [
                'label' => 'Deferido',
                'classes' => 'text-emerald-700 bg-emerald-100 border border-emerald-200',
            ],
            'indeferido' => [
                'label' => 'Indeferido',
                'classes' => 'text-red-700 bg-red-100 border border-red-200',
            ],
        ];

        $statusData = $statusMap[$rawStatus] ?? [
            'label' => ucfirst(str_replace('_', ' ', (string) $rawStatus)),
            'classes' => 'text-slate-700 bg-slate-100 border border-slate-200',
        ];

        $tipoRequisicao = is_string($request->tipoRequisicao)
            ? $request->tipoRequisicao
            : (is_object($request->tipoRequisicao) && isset($request->tipoRequisicao->nome)
                ? $request->tipoRequisicao->nome
                : 'Não especificado');

        $requestIdentifier = $request->key ?? $request->id ?? 'N/A';
        $createdAt = isset($request->created_at)
            ? \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i')
            : date('d/m/Y H:i');
        $detailsUrl = isset($request->id)
            ? url('/requerimentos/' . $request->id)
            : url('/requerimentos');
    @endphp

    <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-6 py-8 text-center text-white sm:px-10">
            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-md ring-4 ring-white/30">
                <img src="{{ asset('img/logo-sre.png') }}" alt="Logo SRE" class="h-10 w-10 object-contain">
            </div>
            <h1 class="text-2xl font-bold leading-tight sm:text-3xl">Nova Requisição Registrada</h1>
            <p class="mt-2 text-sm text-emerald-50">Recebemos sua solicitação no sistema.</p>
        </div>

        <div class="px-6 py-8 sm:px-10">
            <p class="mb-4 text-base leading-7 text-slate-700">Olá <span class="rounded bg-emerald-100 px-2 py-1 font-semibold text-emerald-700">{{ $user->name ?? 'Aluno' }}</span>,</p>
            <p class="mb-4 text-base leading-7 text-slate-700">Sua solicitação de <span class="rounded bg-emerald-100 px-2 py-1 font-semibold text-emerald-700">{{ $tipoRequisicao }}</span> foi registrada com sucesso.</p>

            <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50/70 p-4">
                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Detalhes da solicitação</p>
                <p class="text-sm text-slate-700"><strong>Número:</strong> #{{ $requestIdentifier }}</p>
                <p class="text-sm text-slate-700"><strong>Data de envio:</strong> {{ $createdAt }}</p>
                <p class="text-sm text-slate-700"><strong>Status inicial:</strong>
                    <span class="ml-1 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusData['classes'] }}">{{ $statusData['label'] }}</span>
                </p>
            </div>

            <p class="mb-6 text-base leading-7 text-slate-700">Você receberá novos e-mails sempre que houver movimentação no status do requerimento.</p>

            <div class="text-center">
                <a href="{{ $detailsUrl }}" class="inline-block rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white no-underline hover:bg-emerald-500">Ver detalhes</a>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center sm:px-10">
            <p class="text-xs leading-6 text-slate-500">© {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)</p>
            <p class="text-xs leading-6 text-slate-500">Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>