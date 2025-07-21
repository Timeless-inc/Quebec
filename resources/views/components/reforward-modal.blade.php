<div class="modal fade" id="reencaminharModal-{{ $forwarding->id }}" tabindex="-1" aria-labelledby="reencaminharModalLabel-{{ $forwarding->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reencaminharModalLabel-{{ $forwarding->id }}">
                    <i class="fas fa-share-square mr-2"></i>
                    Reencaminhar Requerimento #{{ $forwarding->requerimento->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('forwardings.reforward.store', $forwarding->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Informações do Requerimento -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detalhes do Requerimento
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Aluno:</strong> {{ $forwarding->requerimento->nomeCompleto }}<br>
                                    <strong>Tipo:</strong> {{ $forwarding->requerimento->tipoRequisicao }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Matrícula:</strong> {{ $forwarding->requerimento->matricula }}<br>
                                    <strong>Data:</strong> {{ $forwarding->requerimento->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Seleção de Destinatário -->
                    <div class="mb-4">
                        <label for="receiver_type_{{ $forwarding->id }}" class="form-label">
                            <i class="fas fa-users mr-2"></i>
                            Tipo de Destinatário:
                        </label>
                        <select class="form-control" id="receiver_type_{{ $forwarding->id }}" onchange="toggleReforwardReceivers({{ $forwarding->id }})">
                            <option value="">Selecione o tipo</option>
                            <option value="coordenador">Coordenador</option>
                            <option value="professor">Professor</option>
                        </select>
                    </div>
                    
                    <div class="mb-4" id="coordenador_reforward_group_{{ $forwarding->id }}" style="display: none;">
                        <label for="coordenador_reforward_id_{{ $forwarding->id }}" class="form-label">
                            <i class="fas fa-user-tie mr-2"></i>
                            Coordenador:
                        </label>
                        <select class="form-control" id="coordenador_reforward_id_{{ $forwarding->id }}" name="receiver_id">
                            <option value="">Selecione um coordenador</option>
                            @php
                                $coordenadores = App\Models\User::where('role', 'Coordenador')
                                    ->where('id', '!=', Auth::id())
                                    ->get();
                            @endphp
                            @foreach ($coordenadores as $coordenador)
                                <option value="{{ $coordenador->id }}">{{ $coordenador->name }} - {{ $coordenador->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4" id="professor_reforward_group_{{ $forwarding->id }}" style="display: none;">
                        <label for="professor_reforward_id_{{ $forwarding->id }}" class="form-label">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            Professor:
                        </label>
                        <select class="form-control" id="professor_reforward_id_{{ $forwarding->id }}" name="receiver_id">
                            <option value="">Selecione um professor</option>
                            @php
                                $professores = App\Models\User::where('role', 'Professor')
                                    ->where('id', '!=', Auth::id())
                                    ->get();
                            @endphp
                            @foreach ($professores as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }} - {{ $professor->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mensagem Interna -->
                    <div class="mb-4">
                        <label for="internal_message_{{ $forwarding->id }}" class="form-label">
                            <i class="fas fa-comment mr-2"></i>
                            Mensagem Interna (opcional):
                        </label>
                        <textarea class="form-control" id="internal_message_{{ $forwarding->id }}" name="internal_message" rows="3" 
                                  placeholder="Adicione uma mensagem interna para o destinatário..."></textarea>
                        <small class="form-text text-muted">
                            Esta mensagem será visível apenas para o destinatário do encaminhamento.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Reencaminhar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleReforwardReceivers(id) {
    const type = document.getElementById('receiver_type_' + id).value;
    const coordGroup = document.getElementById('coordenador_reforward_group_' + id);
    const profGroup = document.getElementById('professor_reforward_group_' + id);
    const coordSelect = document.getElementById('coordenador_reforward_id_' + id);
    const profSelect = document.getElementById('professor_reforward_id_' + id);
    
    if (coordGroup) coordGroup.style.display = 'none';
    if (profGroup) profGroup.style.display = 'none';
    
    if (coordSelect) coordSelect.value = '';
    if (profSelect) profSelect.value = '';
    
    if (type === 'coordenador' && coordGroup) {
        coordGroup.style.display = 'block';
        if (coordSelect) coordSelect.setAttribute('name', 'receiver_id');
        if (profSelect) profSelect.removeAttribute('name');
    } else if (type === 'professor' && profGroup) {
        profGroup.style.display = 'block';
        if (profSelect) profSelect.setAttribute('name', 'receiver_id');
        if (coordSelect) coordSelect.removeAttribute('name');
    }
}
</script>
