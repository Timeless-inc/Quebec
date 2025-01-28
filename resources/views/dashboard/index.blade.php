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
        @if($requerimentos->count() > 0)
            @foreach($requerimentos as $requerimento)
                <x-justificativa-aluno
                    id="{{ $requerimento->id }}"
                    nome="{{ $requerimento->nomeCompleto }}"
                    matricula="{{ $requerimento->matricula }}"
                    email="{{ $requerimento->email }}"
                    cpf="{{ $requerimento->cpf }}"
                    datas="{{ $requerimento->created_at->format('d/m/Y') }}"
                    :andamento="$requerimento->status ?? 'em_andamento'"
                    :anexos="[$requerimento->anexarArquivos]"
                    observacoes="{{ $requerimento->observacoes }}"
                    class="justificativa-item" />
            @endforeach
        @else
            <p>Nenhum requerimento encontrado para este usuário.</p>
        @endif
    </div>
</div>



</x-app-layout>
