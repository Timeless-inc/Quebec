<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#063B2E">
    <link rel="preload" href="/fonts/figtree-variable.ttf" as="font" type="font/ttf" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Paleta e fonte do Manual de Marca SRE v1.0
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        floresta: '#063B2E',
                        esmeralda: '#047857',
                        campus: '#10A557',
                        menta: '#DCF3E6',
                        papel: '#F5F8F4',
                        lima: '#C6F36B',
                        musgo: '#3D5A4E',
                        tinta: '#0C1A15',
                    },
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: 'Figtree';
            src: url('/fonts/figtree-variable.ttf') format('truetype');
            font-weight: 300 900;
            font-display: swap;
        }

        html { scrollbar-width: none; }
        html::-webkit-scrollbar { display: none; }
        ::selection { background: #C6F36B; color: #063B2E; }
        :focus-visible { outline: 3px solid #10A557; outline-offset: 3px; border-radius: 4px; }
        .on-dark :focus-visible { outline-color: #C6F36B; }

        /* Momento único de movimento: o hexágono se desenha e o check atravessa a abertura */
        .draw { stroke-dasharray: 1; animation: draw 2.4s cubic-bezier(0.37, 0, 0.2, 1) 0.3s both; }
        .rise { animation: rise 0.9s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes draw { from { stroke-dashoffset: 1; } to { stroke-dashoffset: 0; } }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        @media (prefers-reduced-motion: reduce) {
            .draw, .rise { animation: none; }
        }
    </style>
    <!-- Regra única de margem lateral (container) e de ritmo vertical (seção), usada em toda a página -->
    <style type="text/tailwindcss">
        @layer components {
            .page-container {
                @apply mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8;
            }

            .page-section {
                @apply py-16 md:py-24;
            }
        }
    </style>
    <title>SRE | IFPE Campus Igarassu</title>
</head>

<body class="font-sans font-medium text-tinta bg-papel antialiased">
    <!-- Header: SRE à esquerda, IFPE à direita, separados por filete fino -->
    <header class="fixed w-full bg-papel border-b border-menta z-50">
        <div class="page-container py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center" aria-label="SRE, página inicial">
                    <img src="/img/sre/sre-horizontal-compacto-positivo.svg" alt="SRE, Sistema de Requerimento Estudantil" class="h-10 md:h-11 w-auto">
                    <span class="mx-3 md:mx-4 h-8 w-px bg-musgo/25" aria-hidden="true"></span>
                    <img src="/img/ifpe.png" alt="Instituto Federal de Pernambuco" class="h-6 md:h-8 w-auto">
                </a>

                <!-- Links desktop -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('login') }}" class="text-musgo hover:text-esmeralda font-semibold underline-offset-4 hover:underline transition-colors">Entrar</a>
                    <a href="{{ url('/register') }}" class="bg-esmeralda hover:bg-floresta text-white font-semibold px-5 py-2.5 rounded-lg transition-colors">Cadastrar</a>
                </div>

                <!-- Botão hamburguer mobile -->
                <button id="mobile-menu-button" type="button" aria-controls="mobile-menu" aria-expanded="false" aria-label="Abrir menu" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-floresta hover:bg-menta">
                    <svg id="hamburguer-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu mobile (inicialmente escondido) -->
        <div id="mobile-menu" class="hidden md:hidden w-full bg-papel border-t border-menta">
            <div class="page-container py-3 space-y-2">
                <a href="{{ route('login') }}" class="block py-3 px-4 text-base font-semibold text-floresta hover:bg-menta rounded-lg">
                    Entrar
                </a>
                <a href="{{ url('/register') }}" class="block py-3 px-4 text-base font-semibold bg-esmeralda text-white hover:bg-floresta rounded-lg text-center">
                    Cadastrar
                </a>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero: fundo Floresta, símbolo gigante a 7% de branco como textura -->
        <section class="on-dark relative overflow-hidden bg-floresta text-white pt-32 pb-16 md:pt-44 md:pb-28">
            <svg class="pointer-events-none absolute -right-40 -bottom-40 w-[44rem] max-w-none opacity-100" viewBox="8 5 104 110" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M105 52 L105 82 L60 108 L15 82 L15 38 L60 12 L75 20.7" stroke="#fff" stroke-opacity=".07" stroke-width="12" />
                <path d="M38 62 L55 79 L100 24" stroke="#C6F36B" stroke-opacity=".16" stroke-width="13" />
            </svg>

            <div class="page-container relative">
                <div class="flex flex-col md:flex-row items-center justify-between gap-12 md:gap-16">
                    <!-- Lado esquerdo: texto -->
                    <div class="md:w-1/2 text-center md:text-left rise">
                        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-[-0.03em] leading-[1.02] mb-5 md:mb-7 text-balance">
                            Sistema de<br>
                            <span class="text-lima">requerimento</span><br>
                            estudantil
                        </h1>
                        <p class="text-menta/90 text-lg md:text-xl leading-relaxed mb-8 md:mb-10 max-w-lg mx-auto md:mx-0">
                            Gerencie suas solicitações acadêmicas em um só lugar, de forma rápida e sem burocracia, com nossa plataforma moderna e intuitiva.
                        </p>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <a href="{{ route('login') }}" class="bg-lima hover:bg-white text-floresta font-bold px-6 py-3.5 rounded-lg transition-colors inline-flex items-center">
                                Acessar Sistema
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Lado direito: foto do campus dentro do símbolo, o check sai pela abertura -->
                    <div class="md:w-1/2 flex items-center justify-center md:justify-end">
                        <svg class="w-64 sm:w-80 md:w-full md:max-w-md" viewBox="8 5 104 110" role="img" aria-label="Foto do campus Igarassu do IFPE dentro do símbolo do SRE">
                            <defs>
                                <clipPath id="hex-foto">
                                    <path d="M97.21 33.50 Q105 38 105.00 47.00 L105.00 73.00 Q105 82 97.21 86.50 L67.79 103.50 Q60 108 52.21 103.50 L22.79 86.50 Q15 82 15.00 73.00 L15.00 47.00 Q15 38 22.79 33.50 L52.21 16.50 Q60 12 67.79 16.50 Z" />
                                </clipPath>
                            </defs>
                            <image href="/img/campus.jpg" x="-45" y="-30" width="150" height="150" clip-path="url(#hex-foto)" preserveAspectRatio="xMidYMid slice" />
                            <g fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path class="draw" pathLength="1" d="M105 52 L105 82 L60 108 L15 82 L15 38 L60 12 L75 20.7" stroke="#fff" stroke-width="12" />
                                <path class="draw" pathLength="1" d="M38 62 L55 79 L100 24" stroke="#063B2E" stroke-width="21" />
                                <path class="draw" pathLength="1" d="M38 62 L55 79 L100 24" stroke="#C6F36B" stroke-width="13" />
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trilha: três nós ligados por uma linha, o último concluído com check -->
        <section class="page-section">
            <div class="page-container">
                <h2 class="sr-only">Como funciona</h2>
                <ol class="grid grid-cols-1 md:grid-cols-3 gap-y-12 md:gap-x-10">
                    <li class="relative pl-20 md:pl-0">
                        <svg class="absolute left-0 top-0 h-14 w-14 md:relative md:z-10 md:block md:mx-auto md:h-16 md:w-16 md:mb-6" viewBox="0 0 120 120" fill="none" stroke-linejoin="round" aria-hidden="true">
                            <path d="M60 12 L105 38 L105 82 L60 108 L15 82 L15 38 Z" fill="#F5F8F4" stroke="#047857" stroke-width="10" />
                        </svg>
                        <span class="absolute left-[26px] top-16 -bottom-12 w-1 rounded-full bg-esmeralda/30 md:left-1/2 md:right-auto md:top-[30px] md:bottom-auto md:h-1 md:w-[calc(100%+2.5rem)]" aria-hidden="true"></span>
                        <h3 class="text-xl md:text-2xl font-bold tracking-[-0.02em] text-floresta mb-3 md:text-center">Solicitações</h3>
                        <p class="text-musgo text-base leading-relaxed max-w-xs md:max-w-sm md:mx-auto md:text-center">
                            Envie seu requerimento com formulários dinâmicos, com upload de arquivos para todos os tipos de solicitação.
                        </p>
                    </li>

                    <li class="relative pl-20 md:pl-0">
                        <svg class="absolute left-0 top-0 h-14 w-14 md:relative md:z-10 md:block md:mx-auto md:h-16 md:w-16 md:mb-6" viewBox="0 0 120 120" fill="none" stroke-linejoin="round" aria-hidden="true">
                            <path d="M60 12 L105 38 L105 82 L60 108 L15 82 L15 38 Z" fill="#F5F8F4" stroke="#047857" stroke-width="10" />
                        </svg>
                        <span class="absolute left-[26px] top-16 -bottom-12 w-1 rounded-full bg-esmeralda/30 md:left-1/2 md:right-auto md:top-[30px] md:bottom-auto md:h-1 md:w-[calc(100%+2.5rem)]" aria-hidden="true"></span>
                        <h3 class="text-xl md:text-2xl font-bold tracking-[-0.02em] text-floresta mb-3 md:text-center">Rastreamento</h3>
                        <p class="text-musgo text-base leading-relaxed max-w-xs md:max-w-sm md:mx-auto md:text-center">
                            Acompanhe o andamento de seus requerimentos com atualizações dos status pelo sistema e por e-mail.
                        </p>
                    </li>

                    <li class="relative pl-20 md:pl-0">
                        <svg class="absolute left-0 top-0 h-14 w-14 md:relative md:z-10 md:block md:mx-auto md:h-16 md:w-16 md:mb-6" viewBox="0 0 120 120" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M60 12 L105 38 L105 82 L60 108 L15 82 L15 38 Z" fill="#047857" stroke="#047857" stroke-width="10" />
                            <path d="M38 62 L55 79 L84 40" stroke="#F5F8F4" stroke-width="13" />
                        </svg>
                        <h3 class="text-xl md:text-2xl font-bold tracking-[-0.02em] text-floresta mb-3 md:text-center">Processos</h3>
                        <p class="text-musgo text-base leading-relaxed max-w-xs md:max-w-sm md:mx-auto md:text-center">
                            Rapidez no processamento dos requerimentos, com notificações automáticas para você acompanhar tudo em tempo real.
                        </p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- Seção "Pronto para começar" -->
        <section class="page-section bg-menta">
            <div class="page-container">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl md:text-4xl font-bold tracking-[-0.02em] text-floresta mb-4 text-balance">Pronto para simplificar seus requerimentos?</h2>
                        <p class="text-musgo text-lg leading-relaxed max-w-lg mx-auto md:mx-0">
                            Nossa plataforma oferece tudo que você precisa para gerenciar suas demandas acadêmicas com rapidez. Esqueça as filas e os papéis.
                        </p>
                    </div>

                    <div class="shrink-0">
                        <a href="{{ url('/register') }}" class="bg-esmeralda hover:bg-floresta text-white font-bold px-6 py-3.5 rounded-lg inline-flex items-center transition-colors">
                            Criar sua conta
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="on-dark bg-floresta text-menta/80 py-12 md:py-16">
        <div class="page-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <img src="/img/sre/sre-horizontal-compacto-negativo.svg" alt="SRE" class="h-12 w-auto mb-4">
                    <p class="max-w-md">
                        Sistema de Requerimento Estudantil<br>
                        IFPE Campus Igarassu
                    </p>
                </div>

                <div>
                    <h4 class="text-sm uppercase font-bold tracking-[0.14em] text-lima mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white underline-offset-4 hover:underline text-sm">Política de privacidade</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm uppercase font-bold tracking-[0.14em] text-lima mb-4">Contato</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-lima shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm">contato@sre.edu.br</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-menta/15 mt-8 pt-8 text-center text-menta/70 text-xs">
                <p>&copy; {{ date('Y') }} Timeless Inc. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript simplificado para hamburguer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const hamburgerIcon = document.getElementById('hamburguer-icon');
            const closeIcon = document.getElementById('close-icon');

            if (!mobileMenuButton || !mobileMenu || !hamburgerIcon || !closeIcon) {
                console.error('Elementos necessários para o menu não encontrados');
                return;
            }

            function setMenuOpen(isOpen) {
                mobileMenu.classList.toggle('hidden', !isOpen);
                hamburgerIcon.classList.toggle('hidden', isOpen);
                closeIcon.classList.toggle('hidden', !isOpen);
                mobileMenuButton.setAttribute('aria-expanded', String(isOpen));
                mobileMenuButton.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            }

            mobileMenuButton.addEventListener('click', function() {
                setMenuOpen(mobileMenu.classList.contains('hidden'));
            });

            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    setMenuOpen(false);
                });
            });
        });
    </script>
</body>

</html>
