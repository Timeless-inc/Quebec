@props(['title', 'photoTitle', 'photoText'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page min-h-dvh bg-white font-sans text-gray-900 antialiased">
    <div class="grid min-h-dvh lg:grid-cols-[5fr_6fr]">
        <aside class="relative isolate flex h-48 flex-col justify-between overflow-hidden bg-emerald-950 p-6 sm:h-56 lg:h-auto lg:p-12">
            <img src="/img/campus.jpg" alt="" width="736" height="736"
                class="absolute inset-0 -z-20 h-full w-full object-cover object-[40%_62%]">
            <div class="absolute inset-x-0 top-0 -z-10 h-32 bg-gradient-to-b from-emerald-950/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 -z-10 h-2/3 bg-gradient-to-t from-emerald-950/85 via-emerald-950/30 to-transparent"></div>

            <div class="flex w-fit items-center gap-3.5 rounded-xl bg-white py-2 pl-2.5 pr-3.5 shadow-lg shadow-black/20" role="img"
                aria-label="Sistema de Requerimento Estudantil, Instituto Federal de Pernambuco">
                <img src="/img/logo-sre.png" alt="" class="size-9 object-contain">
                <span class="h-7 w-px bg-gray-300" aria-hidden="true"></span>
                <img src="/img/ifpe.png" alt="" class="h-8 w-auto sm:h-10">
            </div>

            <div>
                <p class="text-balance text-2xl font-semibold leading-tight text-white lg:text-4xl">{{ $photoTitle }}</p>
                <p class="mt-3 hidden max-w-sm text-base text-emerald-50/90 lg:block">{{ $photoText }}</p>
            </div>
        </aside>

        <main class="flex flex-col px-6 py-6 sm:px-12 lg:px-16 lg:pt-12">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col">
                <a href="{{ url('/') }}" class="auth-button-secondary h-10 w-fit gap-1.5 px-3.5 text-sm lg:mt-2">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Voltar
                </a>

                <div class="flex flex-1 flex-col py-10 lg:justify-center lg:py-16">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>

</html>
