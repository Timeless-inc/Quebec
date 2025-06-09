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