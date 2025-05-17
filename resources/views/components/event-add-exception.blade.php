<div class="modal fade" id="eventExceptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('events.store-exception') }}" method="POST" class="event-exception-form">
                @csrf
                <div class="modal-header bg-yellow-600 text-white">
                    <h5 class="modal-title flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Criar Evento de Exceção
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body bg-gray-50 p-4">
                    <div class="alert alert-warning mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Este evento permitirá que apenas um usuário específico acesse o tipo de requerimento selecionado.</span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-medium text-gray-700 mb-1 block">
                            <i class="fas fa-id-card mr-1"></i> CPF do Usuário
                        </label>
                        <div class="flex">
                            <input 
                                type="text" 
                                class="form-control w-full py-2 px-3 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors cpf-mask" 
                                id="cpf_search" 
                                name="cpf" 
                                placeholder="000.000.000-00" 
                                required
                            >
                            <button 
                                class="btn bg-gray-200 hover:bg-gray-300 transition-colors border border-gray-300 rounded-r-md px-3 flex items-center" 
                                type="button" 
                                id="searchUserBtn"
                            >
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4 hidden transform transition-all duration-300 ease-in-out" id="userFoundContainer">
                        <div id="userFoundSuccess" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md shadow-sm hidden">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-check text-green-500"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-green-800 mb-1">Usuário Encontrado:</h6>
                                    <p class="text-sm text-gray-700" id="userFoundInfo"></p>
                                </div>
                            </div>
                        </div>
                        <div id="userFoundError" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md shadow-sm hidden">
                            <div class="flex items-center">
                                <div class="bg-red-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-times text-red-500"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-red-800 mb-1">Usuário não encontrado</h6>
                                    <p class="text-sm text-gray-700">Verifique o CPF e tente novamente.</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="selectedUserId">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4 col-span-2">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-list-alt mr-1"></i> Tipo de Requerimento
                            </label>
                            <select class="form-select w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" name="requisition_type_id" required>
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

                        <div class="mb-4 col-span-2">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-heading mr-1"></i> Título do Evento
                            </label>
                            <input type="text" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" name="title" placeholder="Ex: Exceção para [Nome do Aluno]">
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-day mr-1"></i> Data de Início
                            </label>
                            <input type="date" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" name="start_date" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-check mr-1"></i> Data de Término
                            </label>
                            <input type="date" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="mt-4 mb-2">
                        <div class="bg-white p-3 rounded-md border border-gray-200 hover:bg-yellow-50 hover:border-yellow-200 transition-colors cursor-pointer group" onclick="toggleExceptionEventActive()">
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer w-full" for="exceptionEventIsActive">
                                    <div class="mr-3">
                                        <i class="fas fa-toggle-on text-green-500 group-hover:text-yellow-600 transition-colors text-xl exception-toggle-icon"></i>
                                    </div>
                                    <div>
                                        <span class="font-medium">Habilitar exceção imediatamente</span>
                                        <p class="text-xs text-gray-500 mt-1">O aluno poderá acessar este requerimento assim que o evento for criado</p>
                                    </div>
                                </label>
                                <input class="form-check-input sr-only" type="checkbox" name="is_active" id="exceptionEventIsActive" checked value="1">
                                <div class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full exception-toggle-status">Ativado</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="is_exception" value="1">
                </div>
                <div class="modal-footer bg-gray-100 flex justify-between">
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-exclamation-circle mr-1"></i> Apenas o usuário selecionado terá acesso a este tipo de requerimento
                    </span>
                    <div>
                        <button type="button" class="btn btn-outline-secondary mr-2 hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn bg-yellow-600 text-white hover:bg-yellow-700 transition-colors" id="saveExceptionBtn" disabled>
                            <i class="fas fa-save mr-1"></i> Salvar Exceção
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchUserBtn = document.getElementById('searchUserBtn');
        const cpfInput = document.getElementById('cpf_search');
        const userFoundContainer = document.getElementById('userFoundContainer');
        const userFoundSuccess = document.getElementById('userFoundSuccess');
        const userFoundError = document.getElementById('userFoundError');
        const userFoundInfo = document.getElementById('userFoundInfo');
        const selectedUserId = document.getElementById('selectedUserId');
        const saveExceptionBtn = document.getElementById('saveExceptionBtn');
        
        const today = new Date();
        const nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 5);
        
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        document.querySelectorAll('#eventExceptionModal input[type="date"]').forEach((input, index) => {
            if (index === 0) {
                input.value = formatDate(today);
            } else {
                input.value = formatDate(nextWeek);
            }
        });

        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); 

            if (value.length > 11) {
                value = value.substring(0, 11); 
            }

            if (value.length <= 3) {
            } else if (value.length <= 6) {
                value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
            } else if (value.length <= 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
            } else {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
            }

            e.target.value = value;
        });

        searchUserBtn.addEventListener('click', function() {
            const cpf = cpfInput.value.replace(/[^\d]/g, ''); 

            if (cpf.length !== 11) {
                alert('Por favor, insira um CPF válido com 11 dígitos.');
                return;
            }
            
            searchUserBtn.disabled = true;
            searchUserBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/api/users/search-by-cpf/${cpf}`)
                .then(response => response.json())
                .then(data => {
                    userFoundContainer.classList.remove('hidden');
                    
                    if (data.success) {
                        userFoundSuccess.classList.remove('hidden');
                        userFoundError.classList.add('hidden');
                        userFoundInfo.textContent = `Nome: ${data.user.name} | Email: ${data.user.email}`;
                        selectedUserId.value = data.user.id;
                        saveExceptionBtn.disabled = false;
                        
                        const titleInput = document.querySelector('input[name="title"]');
                        if (!titleInput.value) {
                            titleInput.value = `Exceção para ${data.user.name}`;
                        }
                    } else {
                        userFoundSuccess.classList.add('hidden');
                        userFoundError.classList.remove('hidden');
                        selectedUserId.value = '';
                        saveExceptionBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar usuário:', error);
                    userFoundSuccess.classList.add('hidden');
                    userFoundError.classList.remove('hidden');
                    selectedUserId.value = '';
                    saveExceptionBtn.disabled = true;
                })
                .finally(() => {
                    searchUserBtn.disabled = false;
                    searchUserBtn.innerHTML = '<i class="fas fa-search"></i>';
                });
        });
    });
    
    function toggleExceptionEventActive() {
        const checkbox = document.getElementById('exceptionEventIsActive');
        checkbox.checked = !checkbox.checked;
        
        const toggleIcon = document.querySelector('.exception-toggle-icon');
        const toggleStatus = document.querySelector('.exception-toggle-status');
        
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