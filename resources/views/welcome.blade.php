<!DOCTYPE html>
<<<<<<< HEAD
<html lang="pt-BR">
=======
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

>>>>>>> cea589139812699c8270fdc8312a85ebdfbcd21f
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SRE | Timeless</title>
<<<<<<< HEAD

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #dce3d5; /* Cor do fundo da seção principal */
        }
        .navbar {
            background-color: white;
            padding: 15px 50px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #10A11A;
            font-size: 24px;
        }
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80vh;
            padding: 0 10%;
        }
        .hero-text {
            max-width: 500px;
            text-align: left;
        }
        .hero-text h1 {
            font-weight: 700;
        }
        .hero-text p {
            font-size: 16px;
            color: #333;
        }
        .btn-green {
            background-color: #10A11A;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .btn-green:hover {
            background-color: #0d8a15;
        }
        .hero-image img {
            max-width: 400px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">SRE.</a>
        <div class="ms-auto">
            <a href="{{ url('/dashboard') }}" class="text-dark me-3">Tutorial</a>
            <a href="{{ url('/dashboard') }}" class="text-dark me-3">Equipe</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-success">Login</a>

        
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <!-- Texto -->
        <div class="hero-text">
            <h1>Sistema de requerimento do estudante.</h1>
            <p>O SRE foi criado para tornar suas solicitações acadêmicas mais rápidas e eficientes. Envie pedidos, acompanhe o status e resolva
                suas pendências sem burocracia, tudo em um só lugar<br>
                Cadastre-se agora e tenha acesso a um ambiente simples e intuitivo para gerenciar suas demandas estudantis!
            </p>
            <a href="{{ route('register') }}" class="btn btn-success ms-2">Cadastre-se</a>
        </div>

        <!-- Imagem -->
        <div class="hero-image">
            <img src="/img/estudante.png" alt="Estudante no computador">
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
=======
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/assets/app.css')))
    <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet">
    @endif
</head>

<body class="antialiased">
    <x-app-home>
        <div class="container text-center mt-5">
            <h1 class="display-4">SRE - Sistema de Requerimento do Estudante</h1>
            <div class="mt-4">
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary ms-2">Register</a>
                @endauth
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</x-app-home>

</html>
>>>>>>> cea589139812699c8270fdc8312a85ebdfbcd21f
