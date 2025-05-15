<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SRE | Cadastro</title>

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
        <div class="w-full max-w-5xl bg-white rounded-xl shadow-xl overflow-hidden">
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
                    <div class="z-10">
                        <h1 class="text-3xl font-bold text-white mb-3">Crie sua conta</h1>
                        <p class="text-white/80 text-sm md:text-base">Preencha seus dados para ter acesso ao sistema.</p>
                    </div>
                </div>


                <div class="md:w-3/5 py-8 px-6 md:px-10 overflow-y-auto max-h-screen">
                    <div class="md:hidden mb-6 flex justify-center">
                        <div class="w-12 h-12 flex items-center justify-center bg-emerald-500 rounded-md p-1">
                            <img src="/img/logo-sre.png" alt="SRE Logo" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="flex justify-left items-left mb-8">
                        <a class="flex items-left" href="#">
                            <img src="/img/ifpe.png" alt="IFPE Logo" class="h-12 w-auto">
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-left text-gray-800 mb-6">Cadastre-se</h2>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Nome de Usuário e Nome Completo lado a lado -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome de Usuário') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="username" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="fulano.silva"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('username')" class="mt-1 text-xs" />
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome Completo') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                    </div>
                                    <input id="name" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Fulano da Silva"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('E-mail') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="seuemail@gmail.com"
                                    class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                        </div>

                        <!-- Matrícula -->
                        <div>
                            <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Matrícula') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="matricula" type="text" name="matricula" :value="old('matricula')" required placeholder="2025IFPEI0001"
                                    class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                            </div>
                            <x-input-error :messages="$errors->get('matricula')" class="mt-1 text-xs" />
                        </div>

                        <!-- Checkbox para segunda matrícula -->
                        <div class="mt-2">
                            <div class="flex items-center">
                                <input id="has_second_matricula" type="checkbox" name="has_second_matricula" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="has_second_matricula" class="ml-2 block text-sm text-gray-700">
                                    {{ __('Possuo uma segunda matrícula') }}
                                </label>
                            </div>
                        </div>

                        <!-- Segunda Matrícula (condicional) -->
                        <div id="second_matricula_container" class="hidden mt-4">
                            <label for="second_matricula" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Segunda Matrícula') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="second_matricula" type="text" name="second_matricula" :value="old('second_matricula')" placeholder="2025IFPEI0002"
                                    class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                            </div>
                            <x-input-error :messages="$errors->get('second_matricula')" class="mt-1 text-xs" />
                        </div>

                        <!-- RG e CPF lado a lado -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- RG -->
                            <div>
                                <label for="rg" class="block text-sm font-medium text-gray-700 mb-1">{{ __('RG') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="rg" type="text" name="rg" :value="old('rg')" required placeholder="00.000.000-0"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('rg')" class="mt-1 text-xs" />
                            </div>

                            <!-- CPF -->
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">{{ __('CPF') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="cpf" type="text" name="cpf" :value="old('cpf')" required placeholder="000.000.000-00"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('cpf')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <!-- Password e Confirm Password lado a lado -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Senha') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="**********"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirme sua senha') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="**********"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between space-x-4 pt-4">
                            <a href="javascript:history.back()" class="flex items-center text-sm text-emerald-600 hover:text-emerald-800">
                                <!-- Ícone de seta para a esquerda -->
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                                {{ __('Voltar') }}
                            </a>

                            <div class="flex items-center space-x-4">
                                <a class="text-sm text-emerald-600 hover:text-emerald-800" href="{{ route('login') }}">
                                    {{ __('Já possui uma conta?') }}
                                </a>

                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (jQuery) {
                jQuery(document).ready(function($) {
                    $("#rg").mask('00.000.000-0');
                    $("#cpf").mask('000.000.000-00', {
                        reverse: true
                    });

                    // Mostrar ou ocultar o campo de segunda matrícula
                    $('#has_second_matricula').on('change', function() {
                        if ($(this).is(':checked')) {
                            $('#second_matricula_container').removeClass('hidden');
                        } else {
                            $('#second_matricula_container').addClass('hidden');
                        }
                    });
                });
            } else {
                console.error('jQuery não está carregado!');
            }

            const hasSecondMatriculaCheckbox = document.getElementById('has_second_matricula');
            const secondMatriculaContainer = document.getElementById('second_matricula_container');
            const secondMatriculaInput = document.getElementById('second_matricula');

            if (hasSecondMatriculaCheckbox && secondMatriculaContainer) {
                hasSecondMatriculaCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        secondMatriculaContainer.classList.remove('hidden');
                        secondMatriculaInput.setAttribute('required', 'required');
                    } else {
                        secondMatriculaContainer.classList.add('hidden');
                        secondMatriculaInput.removeAttribute('required');
                        secondMatriculaInput.value = '';
                    }
                });
            }
        });
    </script>
</body>

</html>