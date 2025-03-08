<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SRE | Timeless</title>

    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header class="cabecalho">
        <a class="cabecalho-logo" href="#">SRE.</a>
        <nav class="cabecalho-menu">
            <a class="cabecalho-menu-item" href="{{ url('/register') }}">Cadastrar</a>       
        </nav>
    </header>

    <main class="conteudo">
        <section class="conteudo-principal">
            <div class="conteudo-principal-escrito">
                <h1 class="conteudo-principal-escrito-titulo">Sistema de <br>Requerimento <br><span class="conteudo-principal-escrito-titulo-estudantil">Estudantil</span></h1>
                <p class="conteudo-principal-escrito-subtitulo">Acesse agora um ambiente simples e intuitivo <br> para gerenciar suas demandas estudantis!</p>
                <div class="botoes-container"> 
                    <a href="{{ url('/dashboard') }}"><button class="conteudo-principal-escrito-botao-acesso">Acessar Sistema</button></a>
                </div>
            </div>
            <img class="conteudo-principal-imagem" src="/img/estudante.png" alt="Imagem do estudante">
        </section>
    </main>

    <footer>

    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>