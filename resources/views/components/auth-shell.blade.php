@props(['title', 'photoTitle', 'photoText', 'backUrl' => null])

@php
    // Uma palavra do título da foto vai em lima (Marca-texto do manual de marca).
    $photoTitleLead = \Illuminate\Support\Str::beforeLast($photoTitle, ' ');
    $photoTitleAccent = \Illuminate\Support\Str::afterLast($photoTitle, ' ');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#063B2E">

    <title>{{ $title }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page min-h-dvh bg-papel font-sans font-medium text-tinta antialiased">
    <div class="grid min-h-dvh lg:grid-cols-[5fr_6fr]">
        <aside class="relative isolate flex h-44 flex-col justify-end overflow-hidden bg-floresta p-6 sm:h-56 lg:h-auto lg:p-12">
            <img src="/img/campus.jpg" alt="" width="736" height="736"
                class="absolute inset-0 -z-20 h-full w-full object-cover object-[40%_62%]">
            <div class="absolute inset-x-0 bottom-0 -z-10 h-3/4 bg-gradient-to-t from-floresta/95 via-floresta/45 to-transparent"></div>

            <div>
                <p class="text-balance text-2xl font-extrabold leading-[1.05] tracking-[-0.03em] text-white lg:text-5xl">
                    {{ $photoTitleLead }} <span class="text-lima">{{ $photoTitleAccent }}</span>
                </p>
                <p class="mt-3 hidden max-w-sm text-lg leading-snug text-menta lg:block">{{ $photoText }}</p>
            </div>
        </aside>

        <main class="flex flex-col px-6 py-6 sm:px-12 lg:px-16 lg:pt-12">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col">
                <div class="flex items-center justify-between gap-4 lg:mt-2">
                    {{-- SRE à esquerda, IFPE à direita, filete fino entre os dois; o SRE nunca é menor que o IFPE --}}
                    <div class="flex items-center gap-3" role="img"
                        aria-label="SRE, Sistema de Requerimento Estudantil, Instituto Federal de Pernambuco">
                        <img src="/img/sre/sre-horizontal-compacto-positivo.svg" alt="" class="h-10 w-auto">
                        <span class="h-7 w-px bg-musgo/25" aria-hidden="true"></span>
                        <img src="/img/ifpe.png" alt="" class="h-6 w-auto sm:h-7">
                    </div>

                    <a href="{{ $backUrl ?? url('/') }}" class="auth-button-secondary h-10 w-fit gap-1.5 px-3 text-sm sm:px-3.5">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        <span class="sr-only sm:not-sr-only">Voltar</span>
                    </a>
                </div>

                <div class="flex flex-1 flex-col py-10 lg:justify-center lg:py-16">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>

</html>
