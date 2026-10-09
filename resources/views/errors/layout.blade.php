<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>SRE | @yield('title')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- CSS embutido: a tela de erro precisa abrir mesmo com o Vite, o banco ou a sessão fora do ar --}}
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            font-family: Figtree, Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            color: #1f2937;
            background-color: #f3f4f6;
            background-image:
                radial-gradient(circle at 12% 18%, rgba(16, 185, 129, 0.16), transparent 38%),
                radial-gradient(circle at 88% 82%, rgba(5, 150, 105, 0.14), transparent 42%);
        }

        .card {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 54rem;
            overflow: hidden;
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.18);
            animation: surgir 0.45s ease-out both;
        }

        .hero {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.75rem 1.5rem 2rem;
            overflow: hidden;
            text-align: center;
            color: #ffffff;
            background: linear-gradient(140deg, #065f46 0%, #059669 55%, #34d399 100%);
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.09);
        }

        .hero::before {
            width: 14rem;
            height: 14rem;
            top: -5rem;
            right: -5rem;
        }

        .hero::after {
            width: 9rem;
            height: 9rem;
            bottom: -3.5rem;
            left: -3rem;
        }

        .mascote {
            position: relative;
            width: 11rem;
            height: 11rem;
            animation: flutuar 4s ease-in-out infinite;
        }

        .mascote svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .mascote .cocando {
            transform-origin: 80px 150px;
            animation: cocar 0.45s ease-in-out infinite alternate;
        }

        .mascote .duvida {
            animation: duvidar 2.4s ease-in-out infinite;
        }

        .mascote .duvida-menor {
            animation-delay: 0.6s;
        }

        .code {
            position: relative;
            margin: 0;
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .caption {
            position: relative;
            margin: 0;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            opacity: 0.85;
        }

        .content {
            flex: 1;
            padding: 2rem 1.5rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
        }

        .brand img {
            height: 2rem;
            width: auto;
        }

        h1 {
            margin: 1.5rem 0 0.5rem;
            font-size: 1.625rem;
            font-weight: 700;
            line-height: 1.25;
            color: #111827;
        }

        .message {
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
            color: #4b5563;
        }

        .tips {
            margin: 1.5rem 0 0;
            padding: 1rem 1.125rem;
            border-radius: 0.625rem;
            border: 1px solid #d1fae5;
            background-color: #ecfdf5;
        }

        .tips p {
            margin: 0 0 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #065f46;
        }

        .tips ul {
            margin: 0;
            padding-left: 1.125rem;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #047857;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }

        .button {
            display: inline-block;
            padding: 0.6875rem 1.375rem;
            font: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0.5rem;
            border: 1px solid #059669;
            background-color: #059669;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.15s, border-color 0.15s, transform 0.15s;
        }

        .button:hover {
            background-color: #047857;
            border-color: #047857;
            transform: translateY(-1px);
        }

        .button:focus-visible {
            outline: 3px solid rgba(16, 185, 129, 0.45);
            outline-offset: 2px;
        }

        .button.secondary {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .button.secondary:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f3f4f6;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .footer img {
            height: 1.75rem;
            width: auto;
        }

        @keyframes surgir {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
        }

        @keyframes flutuar {
            50% {
                transform: translateY(-6px);
            }
        }

        @keyframes cocar {
            from {
                transform: rotate(-3deg);
            }

            to {
                transform: rotate(4deg);
            }
        }

        @keyframes duvidar {
            50% {
                transform: translateY(-5px);
                opacity: 0.55;
            }
        }

        @media (min-width: 768px) {
            .card {
                flex-direction: row;
            }

            .hero {
                flex: 0 0 38%;
                padding: 3rem 1.5rem;
            }

            .code {
                font-size: 6rem;
            }

            .content {
                padding: 2.75rem 2.5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .card,
            .mascote,
            .mascote .cocando,
            .mascote .duvida {
                animation: none;
            }
        }
    </style>
</head>

<body>
    <main class="card">
        <div class="hero">
            <div class="mascote">
                @include('errors.mascote')
            </div>
            <p class="code">@yield('code')</p>
            <p class="caption">@yield('caption', 'Algo não saiu como esperado')</p>
        </div>

        <div class="content">
            <div class="brand">
                <img src="{{ asset('img/logo-sre.png') }}" alt="">
                Sistema de Requerimentos
            </div>

            <h1>@yield('title')</h1>
            <p class="message">@yield('message')</p>

            @hasSection('tips')
                <div class="tips">
                    <p>O que você pode fazer</p>
                    <ul>
                        @yield('tips')
                    </ul>
                </div>
            @endif

            <div class="actions">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <a href="{{ url('/') }}" class="button">Página inicial</a>
                    <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
                @endif
            </div>

            <div class="footer">
                <img src="{{ asset('img/ifpe.png') }}" alt="Instituto Federal de Pernambuco">
                <span>Erro @yield('code')</span>
            </div>
        </div>
    </main>
</body>

</html>
