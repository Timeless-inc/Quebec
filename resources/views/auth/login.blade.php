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
                <div class="hidden md:flex md:w-2/5 p-8 flex-col justify-between relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('/img/campus.jpg');">
                    <div class="absolute inset-0 bg-emerald-900/30 backdrop-blur-sm"></div>
                    
                    <!-- Logo -->
                    <div class="z-10">
                        <div class="w-12 h-12 flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-md p-1">
                            <img src="/img/logo-sre.png" alt="SRE Logo" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <div class="z-10">
                        <h1 class="text-3xl font-bold text-white mb-3">Bem-vindo de volta!</h1>
                        <p class="text-white/80 text-sm md:text-base">Para fazer seus requerimentos, faça login com suas informações.</p>
                    </div>
                </div>

                <div class="w-full md:w-3/5 py-8 px-6 md:px-10">
                    <div class="md:hidden mb-6">
                        <div class="relative w-full h-32 rounded-xl overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('/img/campus.jpg');">
                            <div class="absolute inset-0 bg-emerald-900/40 backdrop-blur-sm"></div>
                            
                            <div class="absolute inset-0 flex flex-col items-center justify-center z-10 p-4">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-md p-1 mb-2">
                                    <img src="/img/logo-sre.png" alt="SRE Logo" class="w-full h-full object-contain">
                                </div>
                                <h1 class="text-xl font-bold text-white text-center">Bem-vindo de volta!</h1>
                                <p class="text-white/90 text-xs text-center mt-1">Faça login para acessar seus requerimentos</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:hidden flex justify-center items-center mb-6">
                        <a class="flex items-center" href="#">
                            <img src="/img/ifpe.png" alt="IFPE Logo" class="h-10 w-auto">
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
                                <!-- Botão para mostrar/esconder senha -->
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer password-toggle" data-target="password">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Lembrar senha e Esqueceu senha -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
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

                        <!-- Botão de login -->
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            {{ __('Entrar') }}
                        </button>

                        <!-- Links de navegação -->
                        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between">
                            <a href="javascript:history.back()" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 text-sm font-medium mb-3 sm:mb-0">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                                {{ __('Voltar') }}
                            </a>
                            
                            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium inline-flex items-center">
                                {{ __('Não tem conta? Cadastre-se') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggles = document.querySelectorAll('.password-toggle');
            
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const eyeIcon = this.querySelector('.eye-icon');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.innerHTML = `
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" />
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
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
        @media (max-width: 640px) {
            .min-h-screen {
                padding: 1rem;
            }
            
            .rounded-xl {
                border-radius: 0.75rem;
            }
            
            h2 {
                font-size: 1.5rem;
                text-align: center;
            }
            
            .py-8 {
                padding-top: 1rem; 
                padding-bottom: 1.5rem;
            }
            
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .h-32 {
                height: 8rem; 
            }
            
            .text-xs {
                font-size: 0.75rem;
                line-height: 1rem;
            }
            
            .pt-6 {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .pt-6 a:first-child {
                margin-bottom: 1rem;
            }
            
            .pt-6 a:last-child {
                padding: 0.5rem 1rem;
                background-color: rgba(16, 185, 129, 0.1);
                border-radius: 0.375rem;
                width: 100%;
                justify-content: center;
            }
        }
        
        [style*="background-image"] {
            transition: transform 10s ease-in-out;
            background-size: 100% auto;
        }
        
        [style*="background-image"]:hover {
            transform: scale(1.05);
        }
        
        input:hover {
            border-color: #10b981;
        }
        
        .password-toggle:hover .eye-icon {
            color: #10b981 !important;
        }
        
        .eye-icon {
            transition: color 0.2s ease-in-out;
        }
        
        input::placeholder {
            color: #9ca3af;
        }
        
        input {
            transition: all 0.2s ease-in-out;
        }
        
        a:focus, button:focus, input:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }
        
        .pt-6 a {
            position: relative;
            transition: all 0.2s ease-in-out;
        }
        
        .pt-6 a:hover {
            transform: translateY(-2px);
        }
    </style>
</body>

</html>