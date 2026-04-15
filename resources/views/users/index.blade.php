<x-app-diretor-geral-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciamento de Usuários') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filtros de busca -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block text-sm font-medium text-gray-700">Pesquisar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Nome, email, CPF, matrícula...">
                        </div>
                        
                        <div class="w-full md:w-1/4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Cargo</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                <option value="Aluno" {{ request('role') == 'Aluno' ? 'selected' : '' }}>Aluno</option>
                                <option value="Cradt" {{ request('role') == 'Cradt' ? 'selected' : '' }}>CRADT</option>

                                <option value="Diretor Geral" {{ request('role') == 'Diretor Geral' ? 'selected' : '' }}>Diretor Geral</option>
                                @foreach($roles as $customRole)
                                    <option value="{{ $customRole->label }}" {{ request('role') == $customRole->label ? 'selected' : '' }}>{{ $customRole->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                            
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Mensagens de sucesso/erro -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Tabela de usuários -->
                <div class="overflow-x-auto p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matrículas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                                @if(auth()->user()->role === 'Manager' || auth()->user()->isDiretorGeral())
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <div>{{ $user->matricula }}</div>
                                        @if($user->second_matricula)
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Segunda: {{ $user->second_matricula }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->cpf }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($user->role == 'Cradt') ? 'bg-green-100 text-green-800' : (($user->role == 'Manager') ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    
                                    @if(auth()->user()->role === 'Manager' || auth()->user()->isDiretorGeral())
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @php
                                            $editRoute   = auth()->user()->isDiretorGeral() ? route('diretor-geral.users.edit', $user->id)   : route('users.edit', $user->id);
                                            $deleteRoute = auth()->user()->isDiretorGeral() ? route('diretor-geral.users.destroy', $user->id) : route('users.destroy', $user->id);
                                        @endphp

                                        <a href="{{ $editRoute }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ $deleteRoute }}" method="POST" class="inline ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div class="px-6 py-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-diretor-geral-layout>