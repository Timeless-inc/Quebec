<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status da Requisição Atualizado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 min-h-screen bg-gradient-to-br from-slate-100 via-emerald-50 to-slate-200 p-5 font-sans text-slate-700">
    @php
        $normalizeStatus = function ($status) {
            $value = mb_strtolower(trim((string) $status));
            $value = str_replace(['á', 'à', 'â', 'ã', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç'], ['a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c'], $value);
            $value = str_replace(['-', ' '], '_', $value);
            return $value;
        };

        $resolveStatus = function ($status) use ($normalizeStatus) {
            $normalized = $normalizeStatus($status);

            if (in_array($normalized, ['em_andamento', 'em_analise'], true)) {
                return ['label' => 'Em Análise', 'classes' => 'text-blue-700 bg-blue-100 border border-blue-200'];
            }

            if ($normalized === 'pendente') {
                return ['label' => 'Pendente', 'classes' => 'text-amber-700 bg-amber-100 border border-amber-200'];
            }

            if (in_array($normalized, ['finalizado', 'aprovado', 'deferido'], true)) {
                return ['label' => 'Aprovado', 'classes' => 'text-emerald-700 bg-emerald-100 border border-emerald-200'];
            }

            if ($normalized === 'indeferido') {
                return ['label' => 'Indeferido', 'classes' => 'text-red-700 bg-red-100 border border-red-200'];
            }

            return [
                'label' => ucfirst(str_replace('_', ' ', $normalized ?: 'desconhecido')),
                'classes' => 'text-slate-700 bg-slate-100 border border-slate-200',
            ];
        };

        $oldStatusData = $resolveStatus($oldStatus ?? '');
        $newStatusData = $resolveStatus($newStatus ?? '');
        $requestIdentifier = $request->key ?? $request->id ?? 'N/A';
        $userName = $request->nomeCompleto ?? 'Aluno';
        $tipoRequisicao = $request->tipoRequisicao ?? 'Não especificado';
        $createdAt = isset($request->created_at)
            ? \Carbon\Carbon::parse($request->created_at)->format('d/m/Y')
            : date('d/m/Y');
        $updatedAt = isset($request->updated_at)
            ? \Carbon\Carbon::parse($request->updated_at)->format('d/m/Y H:i')
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
            <h1 class="text-2xl font-bold leading-tight sm:text-3xl">Status Atualizado</h1>
            <p class="mt-2 text-sm text-emerald-50">Requisição #{{ $requestIdentifier }}</p>
        </div>

        <div class="px-6 py-8 sm:px-10">
            <p class="mb-4 text-base leading-7 text-slate-700">Olá <span class="rounded bg-emerald-100 px-2 py-1 font-semibold text-emerald-700">{{ $userName }}</span>,</p>
            <p class="mb-4 text-base leading-7 text-slate-700">Sua solicitação de <span class="rounded bg-emerald-100 px-2 py-1 font-semibold text-emerald-700">{{ $tipoRequisicao }}</span>, criada em <span class="rounded bg-slate-100 px-2 py-1 font-semibold text-slate-700">{{ $createdAt }}</span>, teve seu status atualizado.</p>

            <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50/70 p-4">
                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Resumo da atualização</p>
                <p class="mb-2 text-sm text-slate-700">
                    <strong>Status anterior:</strong>
                    <span class="ml-1 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $oldStatusData['classes'] }}">{{ $oldStatusData['label'] }}</span>
                </p>
                <p class="mb-2 text-sm text-slate-700">
                    <strong>Novo status:</strong>
                    <span class="ml-1 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $newStatusData['classes'] }}">{{ $newStatusData['label'] }}</span>
                </p>
                <p class="text-sm text-slate-700"><strong>Atualizado em:</strong> {{ $updatedAt }}</p>
            </div>

            <p class="mb-6 text-base leading-7 text-slate-700">Acesse o sistema para ver os detalhes e próximos passos do seu requerimento.</p>

            <div class="text-center">
                <a href="{{ $detailsUrl }}" class="inline-block rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white no-underline hover:bg-emerald-500">Acessar sistema</a>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center sm:px-10">
            <p class="text-xs leading-6 text-slate-500">© {{ date('Y') }} Sistema de Requerimento do Estudante (SRE)</p>
            <p class="text-xs leading-6 text-slate-500">Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>