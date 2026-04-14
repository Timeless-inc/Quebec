<x-app-diretor-geral-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciamento de Cargos') }}
            </h2>
            <a href="{{ route('cargos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Novo Cargo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Cargos Fixos do Sistema -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cargos do Sistema (fixos)</h3>
                    <p class="text-sm text-gray-500 mb-4">Estes cargos são definidos pelo sistema e não podem ser editados.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                        @foreach (['Aluno' => ['bg-blue-100','text-blue-800'], 'Cradt' => ['bg-green-100','text-green-800'], 'Manager' => ['bg-yellow-100','text-yellow-800'], 'Diretor Geral' => ['bg-indigo-100','text-indigo-800']] as $nome => $cor)
                        <div class="flex items-center p-4 rounded-xl border border-gray-200 bg-gray-50">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $cor[0] }} {{ $cor[1] }} mr-3">{{ $nome }}</span>
                            <span class="text-xs text-gray-500">Cargo fixo</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Cargos Dinâmicos -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cargos Criados</h3>
                    <p class="text-sm text-gray-500 mb-4">Cargos personalizados. Usuários com esses cargos podem receber encaminhamentos se habilitado.</p>

                    @if($roles->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users-cog text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Nenhum cargo criado ainda.</p>
                            <a href="{{ route('cargos.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Criar primeiro cargo
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Cargo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pode Receber Encaminhamentos</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuários</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($roles as $role)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ $role->label }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($role->can_receive_forwardings)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    <i class="fas fa-check mr-1"></i> Sim
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full">
                                                    <i class="fas fa-times mr-1"></i> Não
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \App\Models\User::where('role', $role->label)->count() }} usuário(s)
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('cargos.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            <form action="{{ route('cargos.destroy', $role) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir o cargo \'{{ $role->label }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-diretor-geral-layout>
