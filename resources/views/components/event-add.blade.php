<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('events.store') }}" method="POST" class="event-form">
                @csrf
                <div class="modal-header bg-blue-600 text-white">
                    <h5 class="modal-title flex items-center">
                        <i class="fas fa-calendar-plus mr-2"></i> Criar Novo Evento de Requerimento
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body bg-gray-50 p-4">
                    <div class="alert alert-info mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        <span>Preencha os detalhes para criar um novo evento de requerimento.</span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-medium text-gray-700 mb-1 block">
                            <i class="fas fa-list-alt mr-1"></i> Tipo de Requerimento
                        </label>
                        <select class="form-select w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" name="requisition_type_id" required>
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
                    
                    <div class="mb-4">
                        <label class="form-label font-medium text-gray-700 mb-1 block">
                            <i class="fas fa-heading mr-1"></i> Título do Evento
                        </label>
                        <input type="text" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" name="title" placeholder="Ex: Período de Matrícula 2025/1">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-day mr-1"></i> Data de Início
                            </label>
                            <input type="date" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" name="start_date" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-check mr-1"></i> Data de Término
                            </label>
                            <input type="date" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" name="end_date" required>
                        </div>
                    </div>
                    
                    <input type="hidden" name="is_active" value="1">
                </div>
                <div class="modal-footer bg-gray-100 flex justify-between">
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Os eventos só estarão disponíveis para os tipos de requerimentos configurados
                    </span>
                    <div>
                        <button type="button" class="btn btn-outline-secondary mr-2 hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 transition-colors submit-btn" id="saveEventBtn">
                            <i class="fas fa-save mr-1"></i> Salvar Evento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 7);
        
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        document.querySelector('input[name="start_date"]').value = formatDate(today);
        document.querySelector('input[name="end_date"]').value = formatDate(nextWeek);
        
        const eventForm = document.querySelector('.event-form');
        if (eventForm) {
            eventForm.addEventListener('submit', function(e) {
                const spinner = document.getElementById('global-loading-spinner');
                if (spinner) {
                    spinner.classList.remove('hidden');
                }
                
                const saveEventBtn = document.getElementById('saveEventBtn');
                if (saveEventBtn) {
                    saveEventBtn.disabled = true;
                    saveEventBtn.classList.add('opacity-75');
                    saveEventBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Salvando...';
                }
            });
        }
    });
</script>