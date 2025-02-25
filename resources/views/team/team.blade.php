<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SRE | Timeless</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #dce3d5;
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            background-color: white;
            padding: 15px 50px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: #10A11A;
            font-size: 24px;
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
        
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            flex-wrap: wrap;
            padding: 20px;
            gap: 20px;
        }
        
        .card {
            width: 250px;
            height: 300px;
            background-color: #fff;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        .card .img-box {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .card .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter 0.5s ease, transform 0.5s ease; /* Transição para preto e branco */
        }
        
        .card:hover .img-box img {
            filter: grayscale(100%); /* Filtro preto e branco */
            transform: scale(1.1); /* Zoom suave */
        }
        
        .card .card-content {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 20px;
            text-align: center;
            color: white; /* Texto branco para contraste */
            transition: opacity 0.3s ease;
            opacity: 0; /* Invisível por padrão */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centraliza o texto */
        }
        
        .card:hover .card-content {
            opacity: 1; /* Aparece ao passar o mouse */
        }
        
        h1 {
            color: white;
            font-size: 18px;
            margin: 0;
            font-family: 'Figtree', sans-serif;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Sombra para legibilidade */
        }
        
        p {
            font-family: 'Figtree', sans-serif;
            font-size: 12px;
            color: white;
            margin: 5px 0 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Sombra para legibilidade */
        }
    </style>
</head>

<body>
    
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">SRE.</a>
        <div class="ms-auto">
            <a href="{{ url('/dashboard') }}" class="text-dark me-3">Tutorial</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-success">Login</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="img-box">
                <img src="../img/anderson.jpg" alt="Membro6">
            </div>
            <div class="card-content">
                <h1>Anderson</h1>
                <p>FRONT-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/Aristoteles.jpg" alt="Membro2">
            </div>
            <div class="card-content">
                <h1>Aristoteles Lins</h1>
                <p>BACK-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/Brenno.jpg" alt="Membro3">
            </div>
            <div class="card-content">
                <h1>Brenno Victor</h1>
                <p>FRONT-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/luis.jpg" alt="Membro4">
            </div>
            <div class="card-content">
                <h1>Luís Eduardo</h1>
                <p>BACK-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/Dolly.jpg" alt="Membro5">
            </div>
            <div class="card-content">
                <h1>João Pedro</h1>
                <p>BACK-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/Joyce.jpg" alt="Membro6">
            </div>
            <div class="card-content">
                <h1>Joyce Kelle</h1>
                <p>FRONT-END DEVELOPER</p>
            </div>
        </div>
        <div class="card">
            <div class="img-box">
                <img src="../img/kaue.jpg" alt="Membro7">
            </div>
            <div class="card-content">
                <h1>Kauê Lui</h1>
                <p>BACK-END DEVELOPER</p>
            </div>
        </div>
    </div>
</body>
</html>