@props(['title', 'subtitle' => null, 'step'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SRE | Primeiro acesso</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <div class="hidden md:flex md:w-2/5 p-8 flex-col justify-between relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('/img/campus.jpg');">
                    <div class="absolute inset-0 bg-emerald-900/30 backdrop-blur-sm"></div>

                    <div class="z-10">
                        <div class="w-12 h-12 flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-md p-1">
                            <img src="/img/logo-sre.png" alt="SRE Logo" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="z-10">
                        <h1 class="text-3xl font-bold text-white mb-3">Primeiro acesso</h1>
                        <p class="text-white/80 text-sm md:text-base">Seu cadastro é aberto pela CRADT. Confirme seu e-mail institucional para ativar o acesso.</p>
                    </div>
                </div>

                <div class="w-full md:w-3/5 py-8 px-6 md:px-10">
                    <div class="flex items-center mb-6">
                        <img src="/img/ifpe.png" alt="IFPE Logo" class="h-10 w-auto">
                    </div>

                    {{-- Passos do primeiro acesso --}}
                    <ol class="flex items-center gap-2 mb-6 text-xs font-medium text-gray-400">
                        @foreach(['Identificação', 'Código', 'Seus dados'] as $indice => $rotulo)
                            <li class="flex items-center gap-2 {{ $indice + 1 === (int) $step ? 'text-emerald-700' : '' }}">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $indice + 1 <= (int) $step ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500' }}">{{ $indice + 1 }}</span>
                                {{ $rotulo }}
                            </li>
                            @if(!$loop->last)
                                <li class="h-px w-6 bg-gray-200" aria-hidden="true"></li>
                            @endif
                        @endforeach
                    </ol>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h2>
                    @if($subtitle)
                        <p class="text-sm text-gray-500 mb-6">{{ $subtitle }}</p>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
