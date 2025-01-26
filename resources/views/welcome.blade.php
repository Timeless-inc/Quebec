<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SRE | Timeless</title>
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