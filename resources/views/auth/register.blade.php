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
                    <div class="absolute inset-0 bg-emerald-900/30 backdrop-blur-sm"></div>
                    
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
                    
                    <div class="md:flex justify-left items-left mb-8 hidden">
                        <a class="flex items-left" href="#">
                            <img src="/img/ifpe.png" alt="IFPE Logo" class="h-12 w-auto">
                        </a>
                    </div>
                    
                    <div class="md:hidden flex justify-center items-center mb-6">
                        <a class="flex items-center" href="#">
                            <img src="/img/ifpe.png" alt="IFPE Logo" class="h-10 w-auto">
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-left md:text-left text-gray-800 mb-6">Cadastre-se</h2>

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
                                    </div>
                                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="**********"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer password-toggle" data-target="password">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
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
                                    </div>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="**********"
                                        class="py-3 px-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer password-toggle" data-target="password_confirmation">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="pt-4">
                            <!-- Versão Desktop -->
                            <div class="hidden md:flex items-center justify-between">
                                <a href="javascript:history.back()" class="flex items-center text-sm text-emerald-600 hover:text-emerald-800">
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

                            <!-- Versão Mobile -->
                            <div class="md:hidden space-y-4">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:history.back()" class="flex items-center text-sm text-emerald-600 hover:text-emerald-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        {{ __('Voltar') }}
                                    </a>

                                    <a class="text-sm text-emerald-600 hover:text-emerald-800" href="{{ route('login') }}">
                                        {{ __('Já possui uma conta?') }}
                                    </a>
                                </div>

                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
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

            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const eyeIcon = this.querySelector('.eye-icon');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.innerHTML = `
                            <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004 10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752 6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0 00-5.557-5.557z" />
                            <path d="M10.748 13.93l2.523 2.523a9.987 9.987 0 01-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 010-1.186A10.007 10.007 0 012.839 6.02L6.07 9.252a4 4 0 004.678 4.678z" />
                        `;
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.innerHTML = `
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        `;
                    }
                });
            });
        });
    </script>

    <style>
        @media (max-width: 768px) {
            .min-h-screen {
                padding: 1rem;
            }

            .max-h-screen {
                max-height: none;
            }

            .space-y-4>div {
                margin-bottom: 1rem;
            }

            img {
                max-width: 100%;
                height: auto;
            }

            .w-full {
                width: 100% !important;
            }

            button[type="submit"] {
                width: 100%;
                margin-top: 0.5rem;
                padding: 0.75rem 1rem;
            }

            .md\:hidden.space-y-4 {
                margin-top: 1.5rem;
            }
        }

        input:hover {
            border-color: #10b981;
        }

        .password-toggle:hover .eye-icon {
            color: #10b981 !important;
        }

        input::placeholder {
            color: #9ca3af;
        }

        input {
            transition: all 0.2s ease-in-out;
        }

        a:focus,
        button:focus,
        input:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }

        [style*="background-image"] {
            transition: transform 10s ease-in-out;
            background-size: 100% auto;
        }
        
        [style*="background-image"]:hover {
            transform: scale(1.05);
        }
        
        .backdrop-blur-sm {
            backdrop-filter: blur(4px) saturate(120%);
            -webkit-backdrop-filter: blur(4px) saturate(120%);
        }
        
        @media (max-width: 768px) {
            .h-32 {
                height: 8rem; /* Altura fixa para manter proporção */
            }
            
            h2 {
                text-align: center;
            }
            
            .max-h-screen {
                max-height: none;
                overflow-y: visible;
            }
        }
    </style>
</body>

</html>