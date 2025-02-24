<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SRE | Timeless</title>

    <link rel = "stylesheet" type="text/css" href="/css/style.css"
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
   <!-- <style>
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
    </style> -->
</head>

<body>

    <header class="cabecalho">
        <a class="cabecalho-logo" href="#">SRE.</a>
        <nav class="cabecalho-menu">
        <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Início</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Tutorial</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Equipe</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Acessar</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Cadastrar</a>       
        </nav>
    </header>

    <main class="conteudo">
        <section class="conteudo-principal">
            <div class="conteudo-principal-escrito">
                <h1 class="conteudo-principal-escrito-titulo">Sistema de <br>Requerimento <br><span class="conteudo-principal-escrito-titulo-estudantil">Estudantil</span></h1>
                <p class="conteudo-principal-escrito-subtitulo">Acesse agora um ambiente simples e intuitivo <br> para gerenciar suas demandas estudantis!</p>
                <div class="botoes-container"> 
                    <button class="conteudo-principal-escrito-botao-informacao">Saiba Mais</button>
                    <a href="{{ url('/dashboard') }}"><button class="conteudo-principal-escrito-botao-acesso">Acessar Sistema</button></a>
                </div>
            </div>
            <img class="conteudo-principal-imagem" src="/svg/ilustracao.svg" alt="Ilustração do Sistema">
        </section>
    </main>

    <footer>
        
    </footer>
   
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>