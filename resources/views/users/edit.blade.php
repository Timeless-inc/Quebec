<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<x-appcradt>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Usuário') }}
            </h2>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Nome de Usuário</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- CPF -->
                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('cpf')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Matrícula Principal -->
                        <div>
                            <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula Principal</label>
                            <input type="text" name="matricula" id="matricula" value="{{ old('matricula', $user->matricula) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('matricula')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Segunda Matrícula -->
                        <div>
                            <label for="second_matricula" class="block text-sm font-medium text-gray-700">
                                Segunda Matrícula 
                                <span class="text-xs text-gray-500">(opcional)</span>
                            </label>
                            <div class="flex mt-1">
                                <input type="text" name="second_matricula" id="second_matricula" 
                                       value="{{ old('second_matricula', $user->second_matricula) }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                
                                @if($user->second_matricula)
                                    <button type="button" id="clear_second_matricula" 
                                            class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Remover
                                    </button>
                                @endif
                            </div>
                            @error('second_matricula')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- RG -->
                        <div>
                            <label for="rg" class="block text-sm font-medium text-gray-700">RG</label>
                            <input type="text" name="rg" id="rg" value="{{ old('rg', $user->rg) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('rg')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Cargo -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Cargo</label>

                            @if(old('role', $user->role) === 'Manager')
                                <input type="text" value="Manager" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">
                                <input type="hidden" name="role" value="Manager">
                            @else
                                <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="Aluno" {{ (old('role', $user->role) == 'Aluno') ? 'selected' : '' }}>Aluno</option>
                                    <option value="Cradt" {{ (old('role', $user->role) == 'Cradt') ? 'selected' : '' }}>CRADT</option>
                                </select>
                            @endif

                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Botões de ação -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-appcradt>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clearButton = document.getElementById('clear_second_matricula');
        const secondMatriculaInput = document.getElementById('second_matricula');
        
        if (clearButton && secondMatriculaInput) {
            clearButton.addEventListener('click', function() {
                secondMatriculaInput.value = '';
                alert('A segunda matrícula será removida quando você salvar as alterações.');
            });
        }
        
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                
                if (value.length > 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                } else if (value.length > 3) {
                    value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
                }
                
                e.target.value = value;
            });
        }
        
        const rgInput = document.getElementById('rg');
        if (rgInput) {
            rgInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 9) {
                    value = value.substring(0, 9);
                }
                
                if (value.length > 6) {
                    value = value.slice(0, 2) + '.' + value.slice(2, 5) + '.' + value.slice(5);
                } else if (value.length > 2) {
                    value = value.slice(0, 2) + '.' + value.slice(2);
                }
                
                e.target.value = value;
            });
        }
    });
</script>