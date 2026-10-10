<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<x-app-diretor-geral-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Alunos possivelmente duplicados') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Cadastros de alunos com o mesmo CPF, nome ou RG. Confira os dados e mantenha apenas o cadastro correto.
                </p>
            </div>
            <a href="{{ route('diretor-geral.users.index') }}" class="inline-flex w-fit items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-xs hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Voltar aos usuários
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-xs" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @forelse($grupos as $grupo)
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 bg-gray-50 px-6 py-3">
                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Mesmo {{ $grupo['criterio'] }}</span>
                        <span class="text-sm font-medium text-gray-800">{{ $grupo['valor'] }}</span>
                        <span class="text-xs text-gray-500">{{ $grupo['usuarios']->count() }} cadastros</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-3">Aluno</th>
                                    <th class="px-6 py-3">CPF</th>
                                    <th class="px-6 py-3">RG</th>
                                    <th class="px-6 py-3">Matrículas</th>
                                    <th class="px-6 py-3">Cadastro</th>
                                    <th class="px-6 py-3 text-center">Requerimentos</th>
                                    <th class="px-6 py-3 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($grupo['usuarios'] as $user)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID #{{ $user->id }} · {{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->cpf }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->rg }}{{ $user->rg_uf ? ' / ' . $user->rg_uf : '' }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ implode(', ', $user->todasMatriculas()) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->created_at?->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-center text-gray-700">{{ $requerimentosPorUsuario[$user->id] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('diretor-geral.users.edit', $user->id) }}" class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                                    <i class="fas fa-pen mr-1.5"></i>Editar
                                                </a>
                                                <form action="{{ route('diretor-geral.users.destroy', $user->id) }}" method="POST" class="m-0" onsubmit="return confirm('Excluir o cadastro de {{ addslashes($user->name) }} (ID #{{ $user->id }})? Esta ação não pode ser desfeita.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="voltar_para" value="duplicados">
                                                    <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                        <i class="fas fa-trash mr-1.5"></i>Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white px-6 py-12 text-center shadow-md">
                    <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                    <p class="mt-3 font-medium text-gray-800">Nenhum aluno duplicado encontrado</p>
                    <p class="mt-1 text-sm text-gray-500">Não há cadastros de alunos com CPF, nome ou RG repetidos.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-diretor-geral-layout>
