<div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-labelledby="editEventModalLabel{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel{{ $event->id }}">Editar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="requisition_type_id{{ $event->id }}" class="form-label">Tipo de Requerimento</label>
                        <select class="form-select" id="requisition_type_id{{ $event->id }}" disabled>
                            @php
                                $appController = app('App\Http\Controllers\ApplicationController');
                                $tiposRequisicao = $appController->getTiposRequisicao();
                            @endphp
                            
                            @foreach($tiposRequisicao as $id => $tipo)
                                <option value="{{ $id }}" {{ $event->requisition_type_id == $id ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="requisition_type_id" value="{{ $event->requisition_type_id }}">
                        <small class="text-muted mt-1 d-block">
                            O tipo de requerimento não pode ser alterado depois da criação.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="title{{ $event->id }}" class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" id="title{{ $event->id }}" name="title" value="{{ $event->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date{{ $event->id }}" class="form-label">Data de Início</label>
                        <input type="date" class="form-control" id="start_date{{ $event->id }}" name="start_date" value="{{ $event->start_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date{{ $event->id }}" class="form-label">Data de Término</label>
                        <input type="date" class="form-control" id="end_date{{ $event->id }}" name="end_date" value="{{ $event->end_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="eventIsActive{{ $event->id }}" 
                                {{ $event->is_active ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="eventIsActive{{ $event->id }}">
                                Habilitar tipo de requerimento
                            </label>
                        </div>
                        <small class="text-muted">Se desmarcado, este tipo de requerimento não estará disponível mesmo durante o período.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>