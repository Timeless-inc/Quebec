<div class="modal fade" id="eventExceptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('events.store-exception') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Criar Evento de Exceção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Busca por CPF -->
                    <div class="mb-3">
                        <label class="form-label">CPF do Usuário</label>
                        <div class="input-group">
                            <input type="text" class="form-control cpf-mask" id="cpf_search" name="cpf" placeholder="000.000.000-00" required>
                            <button class="btn btn-outline-secondary" type="button" id="searchUserBtn">Buscar</button>
                        </div>
                    </div>

                    <!-- Área para mostrar o usuário encontrado -->
                    <div class="mb-3 d-none" id="userFoundContainer">
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                            <h6 class="text-sm font-semibold text-green-800 mb-1">Usuário Encontrado:</h6>
                            <p class="text-sm text-gray-700" id="userFoundInfo">Nenhum usuário encontrado</p>
                            <input type="hidden" name="user_id" id="selectedUserId">
                        </div>
                    </div>

                    <!-- Campos originais do formulário -->
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
                            <input class="form-check-input" type="checkbox" name="is_active" id="exceptionEventIsActive" checked value="1">
                            <label class="form-check-label" for="exceptionEventIsActive">
                                Habilitar tipo de requerimento
                            </label>
                        </div>
                    </div>
                    <!-- Campo para indicar que é um evento de exceção -->
                    <input type="hidden" name="is_exception" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveExceptionBtn" disabled>Salvar Evento de Exceção</button>
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
        const userFoundInfo = document.getElementById('userFoundInfo');
        const selectedUserId = document.getElementById('selectedUserId');
        const saveExceptionBtn = document.getElementById('saveExceptionBtn');

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
                alert('Por favor, insira um CPF válido.');
                return;
            }

            fetch(`/api/users/search-by-cpf/${cpf}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        userFoundContainer.classList.remove('d-none');
                        userFoundInfo.textContent = `Nome: ${data.user.name} | Email: ${data.user.email}`;
                        selectedUserId.value = data.user.id;
                        saveExceptionBtn.disabled = false;
                    } else {
                        userFoundContainer.classList.remove('d-none');
                        userFoundInfo.textContent = 'Usuário não encontrado';
                        selectedUserId.value = '';
                        saveExceptionBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar usuário:', error);
                    alert('Erro ao buscar usuário. Tente novamente.');
                });
        });
    });
</script>       