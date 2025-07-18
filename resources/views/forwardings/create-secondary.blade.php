<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reencaminhar Requerimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Detalhes do Requerimento -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                            Detalhes do Requerimento
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600"><strong>ID:</strong> #{{ $requerimento->id }}</p>
                                    <p class="text-sm text-gray-600"><strong>Aluno:</strong> {{ $requerimento->nomeCompleto }}</p>
                                    <p class="text-sm text-gray-600"><strong>Matrícula:</strong> {{ $requerimento->matricula }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600"><strong>Tipo:</strong> {{ $requerimento->tipoRequisicao }}</p>
                                    <p class="text-sm text-gray-600"><strong>Status:</strong> 
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $requerimento->status === 'encaminhado' ? 'text-purple-700 bg-purple-100' : 'text-gray-700 bg-gray-100' }}">
                                            {{ ucfirst($requerimento->status) }}
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-600"><strong>Data:</strong> {{ $requerimento->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Reencaminhamento -->
                    <form action="{{ route('forwardings.reforward.store', $forwarding->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-user-cog mr-2 text-green-500"></i>
                                Selecionar Destinatário
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="receiver_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-users mr-2"></i>
                                    Tipo de Destinatário:
                                </label>
                                <select class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="receiver_type" onchange="toggleReceivers()">
                                    <option value="">Selecione o tipo</option>
                                    @if($coordenadores->count() > 0)
                                        <option value="coordenador">Coordenador</option>
                                    @endif
                                    @if($professores->count() > 0)
                                        <option value="professor">Professor</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        @if($coordenadores->count() > 0)
                        <div id="coordenador_group" style="display: none;">
                            <label for="coordenador_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tie mr-2"></i>
                                Coordenador:
                            </label>
                            <select class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="coordenador_id" name="receiver_id">
                                <option value="">Selecione um coordenador</option>
                                @foreach ($coordenadores as $coordenador)
                                    <option value="{{ $coordenador->id }}">
                                        {{ $coordenador->name }} - {{ $coordenador->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if($professores->count() > 0)
                        <div id="professor_group" style="display: none;">
                            <label for="professor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Professor:
                            </label>
                            <select class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="professor_id" name="receiver_id">
                                <option value="">Selecione um professor</option>
                                @foreach ($professores as $professor)
                                    <option value="{{ $professor->id }}">
                                        {{ $professor->name }} - {{ $professor->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label for="internal_message" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment mr-2"></i>
                                Mensagem Interna (opcional):
                            </label>
                            <textarea class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="internal_message" name="internal_message" rows="3" 
                                      placeholder="Adicione uma mensagem interna para o destinatário..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Esta mensagem será visível apenas para o destinatário do encaminhamento.
                            </p>
                        </div>

                        <div class="flex items-center space-x-4 pt-4">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Reencaminhar
                            </button>
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleReceivers() {
        const receiverType = document.getElementById('receiver_type').value;
        const coordenadorGroup = document.getElementById('coordenador_group');
        const professorGroup = document.getElementById('professor_group');
        
        if (coordenadorGroup) coordenadorGroup.style.display = 'none';
        if (professorGroup) professorGroup.style.display = 'none';
        
        const coordenadorSelect = document.getElementById('coordenador_id');
        const professorSelect = document.getElementById('professor_id');
        
        if (coordenadorSelect) coordenadorSelect.value = '';
        if (professorSelect) professorSelect.value = '';
        
        if (receiverType === 'coordenador' && coordenadorGroup) {
            coordenadorGroup.style.display = 'block';
            if (coordenadorSelect) coordenadorSelect.setAttribute('name', 'receiver_id');
            if (professorSelect) professorSelect.removeAttribute('name');
        } else if (receiverType === 'professor' && professorGroup) {
            professorGroup.style.display = 'block';
            if (professorSelect) professorSelect.setAttribute('name', 'receiver_id');
            if (coordenadorSelect) coordenadorSelect.removeAttribute('name');
        }
    }
    </script>
</x-app-layout>
