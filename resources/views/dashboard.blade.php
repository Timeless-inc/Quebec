<title>SRE | Timeless</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('application') }}">
                <button type="button" class="btn btn-success">+ Novo requerimento</button>
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-primary overflow-hidden shadow-sm sm:rounded-lg border border-primary bg-opacity-25">
                        <div class="row">
                            <div class="col-md-4 p-6">
                                <p class="fw-bold">JUSTIFICATIVA DE FALTA</p>
                                <p>Nome: Fulano da Silva</p>
                                <p>Matrícula: 2024TSIIG6778</p>
                                <p>E-mail: kkkk@discente.ifpe.edu.br</p>
                                <p>CPF: 123.456.789-00</p>
                                <p>Data: 18/05/2024</p>
                                <p>Andamento: Em análise</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>