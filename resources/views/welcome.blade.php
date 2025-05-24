<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10A11A',
                        secondary: '#09600f',
                    }
                }
            }
        }
    </script>
    <title>SRE | IFPE</title>
</head>

<body class="font-sans text-gray-800 bg-white">
    <!-- Header com logo e menu hamburguer -->
    <header class="fixed w-full bg-white shadow-sm z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div>
                    <a href="#" class="flex items-center">
                        <img src="/img/logo-sre.png" alt="SRE Logo" class="h-8 md:h-10 w-auto">
                        <span class="mx-2 text-gray-300 font-light">|</span>
                        <img src="/img/ifpe.png" alt="IFPE Logo" class="h-8 md:h-10 w-auto">
                    </a>
                </div>

                <!-- Links desktop -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary font-medium transition-colors">Entrar</a>
                    <a href="{{ url('/register') }}" class="bg-primary hover:bg-secondary text-white font-medium px-5 py-2 rounded-md transition-all border">Cadastrar</a>
                </div>

                <!-- Botão hamburguer mobile -->
                <button id="mobile-menu-button" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100 focus:outline-none">
                    <!-- Ícone Hamburguer -->
                    <svg id="hamburguer-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Ícone X (inicialmente escondido) -->
                    <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu mobile (inicialmente escondido) -->
        <div id="mobile-menu" class="hidden md:hidden w-full bg-white shadow-inner">
            <div class="py-3 space-y-2 px-4">
                <a href="{{ route('login') }}" class="block py-2 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary rounded-md">
                    Entrar
                </a>
                <a href="{{ url('/register') }}" class="block py-2 px-4 text-base font-medium bg-primary text-white hover:bg-secondary rounded-md text-center">
                    Cadastrar
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-28 md:pt-32 pb-16 md:pb-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-12">
                <!-- Lado esquerdo - texto -->
                <div class="md:w-1/2 text-center md:text-left">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 leading-tight">
                        Sistema de<br>
                        <span class="text-primary">requerimento</span><br>
                        do estudante
                    </h1>
                    <p class="text-gray-600 text-base md:text-lg mb-6 md:mb-8 max-w-lg mx-auto md:mx-0">
                        Gerencie suas solicitações acadêmicas em um só lugar, de forma rápida e sem burocracia, com nossa plataforma moderna e intuitiva.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                        <a href="{{ route('login') }}" class="bg-primary hover:bg-secondary text-white font-medium px-5 py-2.5 md:px-6 md:py-3 rounded-md shadow transition-colors inline-flex items-center">
                            Acessar Sistema
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Lado direito - imagem -->
                <div class="md:w-1/2 flex items-center justify-center mt-8 md:mt-0">
                    <img src="/img/estudante.png" alt="Imagem do Estudante" class="w-full max-w-md rounded-xl transform hover:scale-105 transition-all duration-300">
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de recursos com ícones -->
    <section class="py-12 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 md:gap-12">
                <!-- Card 1 -->
                <div class="flex flex-col items-center text-center p-4">
                    <div class="bg-primary/10 p-4 rounded-xl mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Solicitações</h3>
                    <p class="text-gray-600">
                        Envie seu requerimento com formulários dinâmicos, com upload de arquivos para todos os tipos de solicitação.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="flex flex-col items-center text-center p-4">
                    <div class="bg-primary/10 p-4 rounded-xl mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Rastreamento</h3>
                    <p class="text-gray-600">
                        Acompanhe o andamento de seus requerimentos com atualizações dos status pelo sistema e por e-mail.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="flex flex-col items-center text-center p-4 sm:col-span-2 md:col-span-1 mx-auto sm:max-w-lg md:max-w-none">
                    <div class="bg-primary/10 p-4 rounded-xl mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Processos</h3>
                    <p class="text-gray-600">
                        Rapidez no processamento dos requerimentos, com notificações automáticas para você acompanhar tudo em tempo real.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção "Pronto para começar" -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-center md:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Pronto para simplificar seus requerimentos?</h2>
                    <p class="text-gray-600 max-w-lg mx-auto md:mx-0">
                        Nossa plataforma oferece tudo que você precisa para gerenciar suas demandas acadêmicas com rapidez. Esqueça as filas e os papéis.
                    </p>
                </div>

                <div class="mt-4 md:mt-0">
                    <a href="{{ url('/register') }}" class="bg-primary hover:bg-secondary text-white font-medium px-6 py-3 rounded-md inline-flex items-center transition-colors">
                        Criar sua conta
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 py-8 md:py-12">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h4 class="text-sm uppercase font-semibold text-gray-500 mb-4">NOSSO SISTEMA</h4>
                    <p class="text-gray-600 mb-4 max-w-md">
                        Sistema de Requerimento do Estudante
                    </p>
                </div>

                <div>
                    <h4 class="text-sm uppercase font-semibold text-gray-500 mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-primary text-sm">Política de privacidade</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm uppercase font-semibold text-gray-500 mb-4">Contato</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-600 text-sm">contato@sre.edu.br</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 text-center text-gray-500 text-xs">
                <p>&copy; {{ date('Y') }} Timeless Inc. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript simplificado para hamburguer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selecionar elementos
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const hamburgerIcon = document.getElementById('hamburguer-icon');
            const closeIcon = document.getElementById('close-icon');

            // Verificar se elementos existem
            if (!mobileMenuButton || !mobileMenu || !hamburgerIcon || !closeIcon) {
                console.error('Elementos necessários para o menu não encontrados');
                return;
            }

            // Toggle do menu
            mobileMenuButton.addEventListener('click', function() {
                // Toggle da visibilidade do menu
                mobileMenu.classList.toggle('hidden');
                
                // Toggle dos ícones
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Adicionar eventos para links do menu móvel
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Fechar menu quando um link é clicado
                    mobileMenu.classList.add('hidden');
                    // Resetar ícones
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });
            });
        });
    </script>
</body>

</html>