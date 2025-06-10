@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Encaminhar Requerimento</h2>
    
    <div class="card">
        <div class="card-header">Detalhes do Requerimento</div>
        
        <div class="card-body">
            <p><strong>ID:</strong> {{ $requerimento->id }}</p>
            <p><strong>Aluno:</strong> {{ $requerimento->student->name }}</p>
            <p><strong>Tipo:</strong> {{ $requerimento->type }}</p>
            <p><strong>Status:</strong> {{ ucfirst($requerimento->status) }}</p>
            <p><strong>Data de Criação:</strong> {{ $requerimento->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">Encaminhar para</div>
        
        <div class="card-body">
            <form action="{{ route('forwardings.store', $requerimento->id) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="receiver_type">Tipo de Destinatário:</label>
                    <select class="form-control" id="receiver_type" onchange="toggleReceivers()">
                        <option value="">Selecione o tipo</option>
                        <option value="coordenador">Coordenador</option>
                        <option value="professor">Professor</option>
                    </select>
                </div>
                
                <div class="form-group" id="coordenador_group" style="display: none;">
                    <label for="coordenador_id">Coordenador:</label>
                    <select class="form-control" id="coordenador_id" name="receiver_id">
                        <option value="">Selecione um coordenador</option>
                        @foreach ($coordenadores as $coordenador)
                            <option value="{{ $coordenador->id }}">{{ $coordenador->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group" id="professor_group" style="display: none;">
                    <label for="professor_id">Professor:</label>
                    <select class="form-control" id="professor_id" name="receiver_id">
                        <option value="">Selecione um professor</option>
                        @foreach ($professores as $professor)
                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Encaminhar</button>
                <a href="{{ route('requerimentos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">Histórico de Encaminhamentos</div>
        
        <div class="card-body">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requerimento</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aluno</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Encaminhado por</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Encaminhado para</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($forwardings as $forwarding)
                    <tr class="{{ $forwarding->status == 'deferido' ? 'bg-green-50' : ($forwarding->status == 'indeferido' ? 'bg-red-50' : '') }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $forwarding->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $forwarding->requerimento->id }} - {{ $forwarding->requerimento->tipoRequisicao }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $forwarding->requerimento->nomeCompleto }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $forwarding->sender->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $forwarding->receiver->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($forwarding->status)
                                @case('encaminhado')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Encaminhado</span>
                                    @break
                                @case('deferido')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Deferido</span>
                                    @break
                                @case('indeferido')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Indeferido</span>
                                    @break
                                @case('pendente')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                    @break
                                @case('devolvido')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Devolvido</span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($forwarding->status) }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $forwarding->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-2" data-bs-toggle="modal" data-bs-target="#detalhesModal-{{ $forwarding->id }}">Ver Detalhes</a>
                            
                            @if($forwarding->status == 'devolvido')
                            <a href="{{ route('forwardings.create', $forwarding->requerimento->id) }}" class="text-indigo-600 hover:text-indigo-900">Encaminhar Novamente</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleReceivers() {
        const type = document.getElementById('receiver_type').value;
        
        if (type === 'coordenador') {
            document.getElementById('coordenador_group').style.display = 'block';
            document.getElementById('professor_group').style.display = 'none';
            document.getElementById('professor_id').name = '';
            document.getElementById('coordenador_id').name = 'receiver_id';
        } else if (type === 'professor') {
            document.getElementById('professor_group').style.display = 'block';
            document.getElementById('coordenador_group').style.display = 'none';
            document.getElementById('coordenador_id').name = '';
            document.getElementById('professor_id').name = 'receiver_id';
        } else {
            document.getElementById('coordenador_group').style.display = 'none';
            document.getElementById('professor_group').style.display = 'none';
        }
    }
</script>
@endsection