<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Criar Novo Evento de Requerimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Requerimento</label>
                        <select class="form-select" name="requisition_type_id" required>
                            <option value="">Selecione o tipo de requerimento</option>
                            @php
                                $appController = app('App\Http\Controllers\ApplicationController');
                                $tiposRequisicao = $appController->getTiposRequisicao();
                                $tiposComEventos = $appController->getTiposComEventos();
                            @endphp
                            
                            @foreach($tiposRequisicao as $id => $tipo)
                                @if(in_array($id, $tiposComEventos))
                                    <option value="{{ $id }}">{{ $tipo }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Início</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Término</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="eventIsActive" checked value="1">
                            <label class="form-check-label" for="eventIsActive">
                                Habilitar tipo de requerimento
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
