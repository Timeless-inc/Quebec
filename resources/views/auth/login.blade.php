<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SRE | Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Card Container -->
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Left Side - Image Section -->
                <div class="md:w-2/5 p-8 flex flex-col justify-between relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('/img/campus.jpg');">
                    <!-- Logo -->
                    <div class="z-10">
                        <div class="w-12 h-12 flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-md p-1">
                            <img src="/img/logo-sre.png" alt="SRE Logo" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <div class="z-10 ">
                        <h1 class="text-3xl font-bold text-white mb-3">Bem-vindo de volta!</h1>
                        <p class="text-white/80 text-sm md:text-base">Para fazer seus requerimentos, faça login com suas informações.</p>
                    </div>
                </div>

                <div class="md:w-3/5 py-8 px-6 md:px-10">

                    <div class="md:hidden mb-6 flex justify-center">
                        <div class="w-12 h-12 flex items-center justify-center bg-emerald-500 rounded-md">
                            <span class="text-white font-bold">SRE</span>
                        </div>
                    </div>

                    <div class="flex justify-left items-left mb-8">
                        <a class="flex items-left" href="#">
                            <img src="/img/ifpe.png" alt="IFPE Logo" class="h-12 w-auto">
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-left text-gray-800 mb-6">Acesse sua conta</h2>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                    class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full"
                                    placeholder="E-mail">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full"
                                    placeholder="Senha">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Lembrar senha') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                            <a class="text-sm text-emerald-600 hover:text-emerald-800" href="{{ route('password.request') }}">
                                {{ __('Esqueceu sua senha?') }}
                            </a>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            {{ __('Entrar') }}
                        </button>

                        <div class="flex items-center justify-between space-x-4 pt-4">
                            <a href="javascript:history.back()" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                                <!-- Ícone de seta para a esquerda -->
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                                {{ __('Voltar') }}
                            </a>
                            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                                {{ __('Não tem conta? Cadastre-se') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>