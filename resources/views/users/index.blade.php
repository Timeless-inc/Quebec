<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@php
    $isDiretorGeral = auth()->user()->isDiretorGeral();
    $layoutComponent = $isDiretorGeral ? 'app-diretor-geral-layout' : 'app-cradt';
    $indexRoute = $isDiretorGeral ? route('diretor-geral.users.index') : route('users.index');
    $hasFilters = request()->filled('search') || request()->filled('role');
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Gerenciamento de Usuários') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Pesquise, filtre e mantenha os perfis de acesso do sistema.
                </p>
            </div>
            <span class="inline-flex w-fit items-center rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm">
                <i class="fas fa-users mr-2 text-indigo-600"></i>{{ $users->total() }} usuário(s)
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                <div class="border-b border-gray-100 p-6">
                    <form action="{{ $indexRoute }}" method="GET" class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_280px_auto] lg:items-end">
                        <div>
                            <label for="search" class="mb-1 block text-sm font-medium text-gray-700">Pesquisar</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-search text-sm"></i>
                                </span>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-lg border-gray-300 pl-10 text-sm shadow-sm focus:border-indigo-400 focus:ring-indigo-200" placeholder="Nome, email, CPF, matrícula...">
                            </div>
                        </div>

                        <div>
                            <label for="role" class="mb-1 block text-sm font-medium text-gray-700">Cargo</label>
                            <select name="role" id="role" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-400 focus:ring-indigo-200">
                                <option value="">Todos</option>
                                <option value="Aluno" {{ request('role') == 'Aluno' ? 'selected' : '' }}>Aluno</option>
                                <option value="Cradt" {{ request('role') == 'Cradt' ? 'selected' : '' }}>CRADT</option>
                                <option value="Diretor Geral" {{ request('role') == 'Diretor Geral' ? 'selected' : '' }}>Diretor Geral</option>
                                @foreach($roles as $customRole)
                                    <option value="{{ $customRole->label }}" {{ request('role') == $customRole->label ? 'selected' : '' }}>{{ $customRole->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                                <i class="fas fa-filter mr-2"></i>Filtrar
                            </button>
                            <a href="{{ $indexRoute }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                                <i class="fas fa-eraser mr-2"></i>Limpar
                            </a>
                        </div>
                    </form>

                    @if($hasFilters)
                        <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                            <span class="font-medium text-gray-700">Filtros ativos:</span>
                            @if(request('search'))
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Busca: {{ request('search') }}</span>
                            @endif
                            @if(request('role'))
                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">Cargo: {{ request('role') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                @if($users->isEmpty())
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <i class="fas fa-user-slash text-2xl"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">Nenhum usuário encontrado</h3>
                        <p class="mt-1 max-w-md text-sm text-gray-500">Ajuste os filtros para ampliar a busca.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Username</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Matrículas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">CPF</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cargo</th>
                                    @if(auth()->user()->role === 'Manager' || auth()->user()->isDiretorGeral())
                                        <th scope="col" class="w-28 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($users as $user)
                                    @php
                                        $badgeClass = match($user->role) {
                                            'Cradt' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'Manager' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Diretor Geral' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            default => 'bg-blue-100 text-blue-700 border-blue-200',
                                        };
                                        $editRoute = $isDiretorGeral ? route('diretor-geral.users.edit', $user->id) : route('users.edit', $user->id);
                                        $deleteRoute = $isDiretorGeral ? route('diretor-geral.users.destroy', $user->id) : route('users.destroy', $user->id);
                                    @endphp
                                    <tr class="transition-colors hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600">
                                                    {{ mb_substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">ID #{{ $user->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->username }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->matricula }}</div>
                                            @if($user->second_matricula)
                                                <div class="mt-1">
                                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                                        Segunda: {{ $user->second_matricula }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->cpf }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>

                                        @if(auth()->user()->role === 'Manager' || auth()->user()->isDiretorGeral())
                                            <td class="w-28 px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="mx-auto grid w-[72px] grid-cols-2 items-center justify-items-center gap-2">
                                                    <a href="{{ $editRoute }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 transition-colors hover:bg-indigo-100" title="Editar usuário">
                                                        <i class="fas fa-pen text-xs"></i>
                                                    </a>

                                                    @if(auth()->id() !== $user->id)
                                                        <form action="{{ $deleteRoute }}" method="POST" class="m-0 flex h-8 w-8 items-center justify-center">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-700 transition-colors hover:bg-red-100" title="Excluir usuário" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                                <i class="fas fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="h-8 w-8" aria-hidden="true"></span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>
