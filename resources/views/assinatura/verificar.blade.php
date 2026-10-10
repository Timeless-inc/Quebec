<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SRE | Verificar Assinatura</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <a href="{{ Auth::user()->getDashboardRoute() }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo-sre.png') }}" alt="SRE" class="h-9 w-auto">
                <span class="font-semibold text-gray-800">Verificação de Assinatura</span>
            </a>
            <a href="{{ Auth::user()->getDashboardRoute() }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                Voltar ao painel
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">
        <section class="bg-white border border-gray-200 rounded-lg shadow-xs p-5 sm:p-6">
            <h1 class="text-lg font-semibold text-gray-800">Verificar assinatura digital</h1>
            <p class="text-sm text-gray-500 mt-1">
                Informe o código da assinatura digital impresso no rodapé do PDF do requerimento para conferir o documento original.
            </p>

            <form method="GET" action="{{ route('assinatura.verificar') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                <label for="codigo" class="sr-only">Código da assinatura</label>
                <input id="codigo" type="text" name="codigo" value="{{ $codigo }}" required autofocus
                    placeholder="Ex.: 1d752993-489c-4f50-a851-86732dcaedf0"
                    class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-emerald-500 focus:border-emerald-500 p-2.5">
                <button type="submit" class="inline-flex justify-center items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-semibold shadow-xs transition">
                    Verificar
                </button>
            </form>
        </section>

        @if($codigo !== '' && !$requerimento)
            <section class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4" role="alert">
                <p class="font-semibold">Assinatura não encontrada</p>
                <p class="text-sm mt-1">Nenhum requerimento do SRE corresponde a este código. Confira se ele foi digitado exatamente como aparece no documento.</p>
            </section>
        @endif

        @if($requerimento)
            <section class="bg-white border border-gray-200 rounded-lg shadow-xs overflow-hidden">
                <div class="bg-emerald-50 border-b border-emerald-100 px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="font-semibold text-emerald-800">Assinatura válida</p>
                        <p class="text-sm text-emerald-700">Este código pertence a um requerimento registrado no SRE.</p>
                    </div>
                    <a href="{{ route('assinatura.pdf', ['chave' => $requerimento->key, 'download' => 1]) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-semibold shadow-xs transition">
                        Baixar PDF
                    </a>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 px-5 sm:px-6 py-5 text-sm">
                    <div>
                        <dt class="text-gray-500">Requerimento</dt>
                        <dd class="font-medium text-gray-900">#{{ $requerimento->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tipo</dt>
                        <dd class="font-medium text-gray-900">{{ $requerimento->tipoRequisicao }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Requerente</dt>
                        <dd class="font-medium text-gray-900">{{ $requerimento->nomeCompleto }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd class="font-medium text-gray-900">{{ \App\Services\StatusFormatter::format($requerimento->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Aberto em</dt>
                        <dd class="font-medium text-gray-900">{{ $requerimento->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Assinatura</dt>
                        <dd class="font-medium text-gray-900 break-all">{{ $requerimento->key }}</dd>
                    </div>
                </dl>

                <div class="px-5 sm:px-6 pb-6">
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Documento gerado por esta assinatura</h2>
                    <iframe src="{{ route('assinatura.pdf', $requerimento->key) }}" title="PDF do requerimento #{{ $requerimento->id }}" class="w-full h-[75vh] border border-gray-200 rounded-md bg-gray-50"></iframe>
                </div>
            </section>
        @endif
    </main>
</body>

</html>
