<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SRE') }} - Requerimento Finalizado</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen">
       

        <!-- Page Content -->
        <main class="w-100 d-flex justify-content-center align-items-start pt-20">
            <div class="max-w-md mx-auto p-2">
                <div class="bg-white shadow-md rounded-lg p-8 text-center border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Requerimento Finalizado</h2>
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/check.png') }}" alt="Requerimento Finalizado" class="w-40 h-40">
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success mb-6" role="alert">
                            <h3 class="text-lg font-medium text-green-700">{{ session('success') }}</h3>
                        </div>
                    @else
                        <div class="alert alert-success mb-6" role="alert">
                            <h3 class="text-lg font-medium text-green-700">Seu requerimento foi enviado com sucesso!</h3>
                        </div>
                    @endif
                    <p class="text-gray-600 text-sm mb-8">Obrigado por utilizar o SRE. <BR> Seu pedido já foi registrado no sistema.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-success px-6 py-3 rounded text-white shadow-md bg-green-600 hover:bg-green-700 transition-colors">
                        Voltar para o Dashboard
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>