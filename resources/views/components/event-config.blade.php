<div class="modal fade" id="configureTypesModal" tabindex="-1" role="dialog" aria-labelledby="configureTypesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="configureTypesModalLabel">
                    <i class="fas fa-cog me-2"></i> Configurar Tipos de Requerimentos que Necessitam de Evento
                </h5>
                <button type="button" class="btn-close bg-white" onclick="closeConfigModal()" aria-label="Fechar"></button>
            </div>
            <form action="{{ route('events.configure-required-types') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i> 
                        Selecione os tipos de requerimentos que só podem ser criados durante eventos ativos.
                    </div>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="searchRequirementTypes" 
                                placeholder="Buscar tipos de requerimento..."
                                oninput="filterRequirementTypes()"
                            >
                        </div>
                    </div>
                    
                    <div class="mb-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAllTypes()">
                            <i class="fas fa-check-double me-1"></i> Selecionar Todos
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllTypes()">
                            <i class="fas fa-times me-1"></i> Limpar Seleção
                        </button>
                    </div>

                    <div class="row requirement-types-container max-h-[350px] overflow-y-auto">
                        @php
                        $applicationController = app(App\Http\Controllers\ApplicationController::class);
                        $allTypes = $applicationController->getTiposRequisicao();
                        $tiposComEventos = $applicationController->getTiposComEventos();
                        @endphp

                        @foreach($allTypes as $id => $nome)
                        <div class="col-md-6 mb-2 requirement-type-item">
                            <div class="card p-2 h-100 {{ in_array($id, $tiposComEventos) ? 'border-primary border-2' : 'border-light' }} requirement-type-card transform transition-transform duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-md">
                                <div class="form-check">
                                    <input 
                                        type="checkbox"
                                        class="form-check-input requirement-type-checkbox"
                                        id="type-{{ $id }}"
                                        name="required_types[]"
                                        value="{{ $id }}"
                                        data-name="{{ strtolower($nome) }}"
                                        {{ in_array($id, $tiposComEventos) ? 'checked' : '' }}
                                        onchange="updateCardStyle(this)"
                                    >
                                    <label class="form-check-label" for="type-{{ $id }}">
                                        <strong>{{ $nome }}</strong>
                                        <small class="text-muted d-block">ID: {{ $id }}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="me-auto" id="selectedCount">
                        <span class="badge bg-primary">0</span> tipos selecionados
                    </span>
                    <button type="button" class="btn btn-secondary" onclick="closeConfigModal()">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Salvar Configuração
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openConfigModal() {
        const modal = document.getElementById('configureTypesModal');
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
        
        updateSelectedCount();
    }

    function closeConfigModal() {
        const modal = document.getElementById('configureTypesModal');
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.parentNode.removeChild(backdrop);
        }
    }

    function filterRequirementTypes() {
        const searchText = document.getElementById('searchRequirementTypes').value.toLowerCase();
        const items = document.querySelectorAll('.requirement-type-item');
        
        let foundCount = 0;
        
        items.forEach(item => {
            const checkbox = item.querySelector('.requirement-type-checkbox');
            const typeName = checkbox.getAttribute('data-name');
            const typeId = checkbox.value;
            
            if (typeName.includes(searchText) || typeId.includes(searchText)) {
                item.style.display = '';
                foundCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (foundCount === 0) {
            const container = document.querySelector('.requirement-types-container');
            
            let noResultsMsg = document.getElementById('no-results-message');
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'no-results-message';
                noResultsMsg.className = 'col-12 text-center py-4 text-muted';
                noResultsMsg.innerHTML = '<i class="fas fa-search me-2"></i> Nenhum tipo de requerimento encontrado com este termo';
                container.appendChild(noResultsMsg);
            }
        } else {
            const noResultsMsg = document.getElementById('no-results-message');
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
    }
    
    function updateCardStyle(checkbox) {
        const card = checkbox.closest('.requirement-type-card');
        if (checkbox.checked) {
            card.classList.remove('border-light');
            card.classList.add('border-primary', 'border-2');
        } else {
            card.classList.remove('border-primary', 'border-2');
            card.classList.add('border-light');
        }
        
        updateSelectedCount();
    }
    
    function selectAllTypes() {
        const checkboxes = document.querySelectorAll('.requirement-type-checkbox');
        checkboxes.forEach(checkbox => {
            const item = checkbox.closest('.requirement-type-item');
            if (item.style.display !== 'none') {  
                checkbox.checked = true;
                updateCardStyle(checkbox);
            }
        });
    }
    
    function deselectAllTypes() {
        const checkboxes = document.querySelectorAll('.requirement-type-checkbox');
        checkboxes.forEach(checkbox => {
            const item = checkbox.closest('.requirement-type-item');
            if (item.style.display !== 'none') {  
                checkbox.checked = false;
                updateCardStyle(checkbox);
            }
        });
    }
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.requirement-type-checkbox:checked').length;
        const countElement = document.querySelector('#selectedCount .badge');
        countElement.textContent = selectedCount;
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('configureTypesModal');
        if (event.target === modal) {
            closeConfigModal();
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('configureTypesModal');
        if (modal) {
            updateSelectedCount();
        }
    });
</script>