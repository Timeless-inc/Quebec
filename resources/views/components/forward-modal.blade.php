<div class="modal fade" id="encaminharModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="encaminharModalLabel-{{ $requerimento->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encaminharModalLabel-{{ $requerimento->id }}">Encaminhar Requerimento #{{ $requerimento->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('forwardings.store', $requerimento->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="receiver_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Destinatário:</label>
                        <select class="form-control" id="receiver_type_{{ $requerimento->id }}" onchange="toggleReceivers({{ $requerimento->id }})">
                            <option value="">Selecione o tipo</option>
                            <option value="coordenador">Coordenador</option>
                            <option value="professor">Professor</option>
                        </select>
                    </div>
                    
                    <div class="mb-4" id="coordenador_group_{{ $requerimento->id }}" style="display: none;">
                        <label for="coordenador_id_{{ $requerimento->id }}" class="block text-sm font-medium text-gray-700 mb-1">Coordenador:</label>
                        <select class="form-control" id="coordenador_id_{{ $requerimento->id }}" name="receiver_id">
                            <option value="">Selecione um coordenador</option>
                            @php
                                $coordenadores = App\Models\User::where('role', 'Coordenador')->get();
                            @endphp
                            @foreach ($coordenadores as $coordenador)
                                <option value="{{ $coordenador->id }}">{{ $coordenador->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4" id="professor_group_{{ $requerimento->id }}" style="display: none;">
                        <label for="professor_id_{{ $requerimento->id }}" class="block text-sm font-medium text-gray-700 mb-1">Professor:</label>
                        <select class="form-control" id="professor_id_{{ $requerimento->id }}" name="receiver_id">
                            <option value="">Selecione um professor</option>
                            @php
                                $professores = App\Models\User::where('role', 'Professor')->get();
                            @endphp
                            @foreach ($professores as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Encaminhar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleReceivers(id) {
    const type = document.getElementById('receiver_type_' + id).value;
    
    if (type === 'coordenador') {
        document.getElementById('coordenador_group_' + id).style.display = 'block';
        document.getElementById('professor_group_' + id).style.display = 'none';
        document.getElementById('professor_id_' + id).name = '';
        document.getElementById('coordenador_id_' + id).name = 'receiver_id';
    } else if (type === 'professor') {
        document.getElementById('professor_group_' + id).style.display = 'block';
        document.getElementById('coordenador_group_' + id).style.display = 'none';
        document.getElementById('coordenador_id_' + id).name = '';
        document.getElementById('professor_id_' + id).name = 'receiver_id';
    } else {
        document.getElementById('coordenador_group_' + id).style.display = 'none';
        document.getElementById('professor_group_' + id).style.display = 'none';
    }
}
</script>