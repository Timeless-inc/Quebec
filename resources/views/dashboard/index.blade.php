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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <div class="p-6 text-gray-900">
                            <div class="row">
                                <div class="col">
                                    <h3>Requerimento #{{ $requerimento->id }}</h3>
                                    <p><strong>Nome:</strong> {{ $requerimento->nomeCompleto }}</p>
                                    <p><strong>Matrícula:</strong> {{ $requerimento->matricula }}</p>
                                    <p><strong>Email:</strong> {{ $requerimento->email }}</p>
                                    <p><strong>CPF:</strong> {{ $requerimento->cpf }}</p>
                                    <p><strong>Data:</strong> {{ $requerimento->created_at->format('d/m/Y') }}</p>
                                    
                                    <div class="status-section mt-3">
                                        <strong>Status:</strong>
                                        @switch($requerimento->status ?? 'em_andamento')
                                            @case('em_andamento')
                                                <span class="badge bg-primary">Em Andamento</span>
                                                @break
                                            @case('finalizado')
                                                <span class="badge bg-success">Finalizado</span>
                                                @break
                                            @case('indeferido')
                                                <span class="badge bg-danger">Indeferido</span>
                                                @break
                                            @case('pendente')
                                                <span class="badge bg-warning">Pendente</span>
                                                @break
                                        @endswitch
                                    </div>
                                    
                                    <p class="mt-3"><strong>Observações:</strong> {{ $requerimento->observacoes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Nenhum requerimento encontrado para este usuário.</p>
            @endif
        </div>
    </div>
</x-app-layout>
