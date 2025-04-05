<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('events.store') }}" method="POST">
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
                    
                    <div class="mt-4 mb-2">
                        <div class="bg-white p-3 rounded-md border border-gray-200 hover:bg-blue-50 hover:border-blue-200 transition-colors cursor-pointer group" onclick="toggleEventActive()">
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer w-full" for="eventIsActive">
                                    <div class="mr-3">
                                        <i class="fas fa-toggle-on text-green-500 group-hover:text-blue-500 transition-colors text-xl toggle-icon"></i>
                                    </div>
                                    <div>
                                        <span class="font-medium">Habilitar evento imediatamente</span>
                                        <p class="text-xs text-gray-500 mt-1">O evento estará disponível para os alunos assim que for criado</p>
                                    </div>
                                </label>
                                <input class="form-check-input sr-only" type="checkbox" name="is_active" id="eventIsActive" checked value="1">
                                <div class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full toggle-status">Ativado</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-100 flex justify-between">
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Os eventos só estarão disponíveis para os tipos de requerimentos configurados
                    </span>
                    <div>
                        <button type="button" class="btn btn-outline-secondary mr-2 hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 transition-colors">
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
    });
    
    function toggleEventActive() {
        const checkbox = document.getElementById('eventIsActive');
        checkbox.checked = !checkbox.checked;
        
        const toggleIcon = document.querySelector('.toggle-icon');
        const toggleStatus = document.querySelector('.toggle-status');
        
        if (checkbox.checked) {
            toggleIcon.classList.remove('fa-toggle-off', 'text-gray-500');
            toggleIcon.classList.add('fa-toggle-on', 'text-green-500');
            
            toggleStatus.classList.remove('bg-gray-100', 'text-gray-800');
            toggleStatus.classList.add('bg-green-100', 'text-green-800');
            toggleStatus.textContent = 'Ativado';
        } else {
            toggleIcon.classList.remove('fa-toggle-on', 'text-green-500');
            toggleIcon.classList.add('fa-toggle-off', 'text-gray-500');
            
            toggleStatus.classList.remove('bg-green-100', 'text-green-800');
            toggleStatus.classList.add('bg-gray-100', 'text-gray-800');
            toggleStatus.textContent = 'Desativado';
        }
    }
</script>