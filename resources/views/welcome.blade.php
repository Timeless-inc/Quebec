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
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Início</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Tutorial</a>
            <a class="cabecalho-menu-item" href="{{ url('/dashboard') }}">Equipe</a>
            <a class="cabecalho-menu-item" href="{{ route('login') }}">Acessar</a>
            <a class="cabecalho-menu-item" href="{{ route('register') }}">Cadastrar</a>
        </nav>
    </header>

    <main class="conteudo">
        <section class="conteudo-principal">
            <div class="conteudo-principal-escrito">
                <h1 class="conteudo-principal-escrito-titulo">Sistema de <br>Requerimento <br><span class="conteudo-principal-escrito-titulo-estudantil">Estudantil</span></h1>
                <p class="conteudo-principal-escrito-subtitulo">Acesse agora um ambiente simples e intuitivo <br> para gerenciar suas demandas estudantis!</p>
                <div class="botoes-container">
                    <button class="conteudo-principal-escrito-botao-informacao">Saiba Mais</button>
                    <a href="{{ route('login') }}"><button class="conteudo-principal-escrito-botao-acesso">Acessar Sistema</button></a>
                </div>
            </div>
            <img class="conteudo-principal-imagem" src="/svg/ilustracao.svg" alt="Ilustração do Sistema">
        </section>


        <section class="conteudo-desenvolvedor">
            <h1 class="conteudo-desenvolvedor-titulo">Nossa equipe de desenvolvedores</h1>

            <div class="carousel-container">
                <div class="container" id="cardContainer">
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/anderson.jpg" alt="Membro1">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Anderson</h1>
                            <p class="card-content-p">FRONT-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/Aristoteles.jpg" alt="Membro2">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Aristoteles Lins</h1>
                            <p class="card-content-p">BACK-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/Brenno.jpg" alt="Membro3">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Brenno Victor</h1>
                            <p class="card-content-p">FRONT-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/luis.jpg" alt="Membro4">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Luís Eduardo</h1>
                            <p class="card-content-p">BACK-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/Dolly.jpg" alt="Membro5">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">João Pedro</h1>
                            <p class="card-content-p">BACK-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/Joyce.jpg" alt="Membro6">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Joyce Kelle</h1>
                            <p class="card-content-p">FRONT-END DEVELOPER</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="img-box">
                            <img src="../img/kaue.jpg" alt="Membro7">
                        </div>
                        <div class="card-content">
                            <h1 class="card-content-title">Kauê Lui</h1>
                            <p class="card-content-p">BACK-END DEVELOPER</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-btn prev-btn" onclick="voltarSlide()">❮</button>
                <button class="carousel-btn next-btn" onclick="proximoSlide()">❯</button>
            </div>

        </section>

    </main>

    <footer>

    </footer>

    <script>
        const container = document.getElementById('cardContainer');
        const cards = document.querySelectorAll('.card');
        const cardWidth = cards[0].offsetWidth + 20; // Largura do card + gap
        let currentIndex = 0;
        const visibleCards = 3; // Quantos cards aparecem por vez
        const maxIndex = cards.length - visibleCards;

        function updateCarousel() {
            container.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }

        function proximoSlide() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }

        function voltarSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }
    </script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>