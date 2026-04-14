<x-app-cradt>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Cargo') }}: <span class="text-indigo-600">{{ $cargo->label }}</span>
            </h2>
            <a href="{{ route('cargos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php $userCount = \App\Models\User::where('role', $cargo->label)->count(); @endphp
                @if($userCount > 0)
                    <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-700 p-4 mb-6 rounded">
                        <p class="text-sm"><i class="fas fa-exclamation-triangle mr-1"></i>
                            Este cargo possui <strong>{{ $userCount }} usuário(s)</strong> vinculado(s).
                            Ao alterar o nome, todos esses usuários terão o cargo atualizado automaticamente.
                        </p>
                    </div>
                @endif

                <form action="{{ route('cargos.update', $cargo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-1">
                            Nome do Cargo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label', $cargo->label) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('label')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="hidden" name="can_receive_forwardings" value="0">
                            <input type="checkbox" name="can_receive_forwardings" value="1" id="can_receive_forwardings"
                                   {{ old('can_receive_forwardings', $cargo->can_receive_forwardings) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Pode receber encaminhamentos</span>
                                <p class="text-xs text-gray-500">Usuários com este cargo aparecerão na lista de destinatários do CRADT.</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('cargos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <i class="fas fa-save mr-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-cradt>
