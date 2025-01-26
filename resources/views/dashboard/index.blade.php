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
            <!-- Exemplo de chamada do componente -->
            <x-justificativa-aluno
                nome="Fulano da Silva"
                matricula="2024TSIIG6778"
                email="kkkk@discente.ifpe.edu.br"
                cpf="123.456.789-00"
                :datas="$datas" 
                andamento="Pendência"
                :anexos="['requerimento_TSI202420892.png', 'hbshdbfhbaajcmsncanjbs.png', 'bshdbfhbaajcmsnjcanbs.img']"
                observacoes="O aluno justificou sua falta alegando problemas de saúde e anexou atestados médicos correspondentes."
            />
        </div>
    </div>
</x-app-layout>
