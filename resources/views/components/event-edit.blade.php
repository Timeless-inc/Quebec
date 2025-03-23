<div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" name="title" value="{{ $event->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Início</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $event->start_date }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Término</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $event->end_date }}" required>
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