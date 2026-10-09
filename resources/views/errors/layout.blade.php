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
            padding: 1rem;
            font-family: Figtree, Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .card {
            width: 100%;
            max-width: 32rem;
            padding: 2.5rem 2rem;
            text-align: center;
            background-color: #ffffff;
            border-radius: 0.75rem;
            border-top: 4px solid #059669;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            height: 3rem;
            width: auto;
            margin-bottom: 1.5rem;
        }

        .code {
            margin: 0;
            font-size: 4.5rem;
            font-weight: 700;
            line-height: 1;
            color: #059669;
        }

        h1 {
            margin: 1rem 0 0.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .message {
            margin: 0 auto;
            max-width: 26rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #6b7280;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2rem;
        }

        .button {
            display: inline-block;
            padding: 0.625rem 1.25rem;
            font: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0.375rem;
            border: 1px solid #059669;
            background-color: #059669;
            color: #ffffff;
            cursor: pointer;
        }

        .button:hover {
            background-color: #047857;
            border-color: #047857;
        }

        .button.secondary {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .button.secondary:hover {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }

        .footer img {
            height: 2rem;
            width: auto;
        }
    </style>
</head>

<body>
    <main class="card">
        <img src="{{ asset('img/logo-sre.png') }}" alt="SRE" class="logo">

        <p class="code">@yield('code')</p>
        <h1>@yield('title')</h1>
        <p class="message">@yield('message')</p>

        <div class="actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
                <a href="{{ url('/') }}" class="button">Página inicial</a>
            @endif
        </div>

        <div class="footer">
            <img src="{{ asset('img/ifpe.png') }}" alt="Instituto Federal de Pernambuco">
        </div>
    </main>
</body>

</html>
