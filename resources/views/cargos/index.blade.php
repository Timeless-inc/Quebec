<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<x-app-diretor-geral-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Gerenciamento de Cargos') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Configure cargos personalizados e quem pode receber encaminhamentos.
                </p>
            </div>
            <button type="button" id="openCreateRoleModalHeader" class="inline-flex w-fit items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>Novo cargo
            </button>
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

            <div class="space-y-6">
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Cargos do sistema</h3>
                                <p class="mt-1 text-sm text-gray-500">Esses cargos são nativos e não podem ser editados.</p>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Fixos</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ([
                            'Aluno' => ['bg-blue-50', 'text-blue-700', 'border-blue-200', 'fas fa-user-graduate'],
                            'Cradt' => ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200', 'fas fa-user-cog'],
                            'Diretor Geral' => ['bg-indigo-50', 'text-indigo-700', 'border-indigo-200', 'fas fa-user-shield'],
                        ] as $nome => $cor)
                            <div class="rounded-lg border {{ $cor[2] }} bg-white p-3 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg {{ $cor[0] }} {{ $cor[1] }}">
                                        <i class="{{ $cor[3] }} text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $nome }}</p>
                                        <p class="text-xs text-gray-500">Cargo fixo</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                    <div class="border-b border-gray-100 px-6 py-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Cargos criados</h3>
                                <p class="mt-1 text-sm text-gray-500">Cargos personalizados podem receber encaminhamentos quando habilitados.</p>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $roles->count() }} personalizado(s)</span>
                        </div>
                    </div>

                    @if($roles->isEmpty())
                        <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fas fa-users-cog text-2xl"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">Nenhum cargo criado ainda</h3>
                            <p class="mt-1 max-w-md text-sm text-gray-500">Crie um cargo para liberar novos painéis e opções de encaminhamento.</p>
                            <button type="button" id="openCreateRoleModalEmpty" class="mt-5 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>Criar primeiro cargo
                            </button>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cargo</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Encaminhamentos</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Usuários</th>
                                        <th class="w-28 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($roles as $role)
                                        @php $userCount = \App\Models\User::where('role', $role->label)->count(); @endphp
                                        <tr class="transition-colors hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-purple-50 text-purple-700">
                                                        <i class="fas fa-id-badge"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $role->label }}</p>
                                                        <p class="text-xs text-gray-500">{{ $role->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($role->can_receive_forwardings)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                        <i class="fas fa-check mr-1.5"></i>Habilitado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                        <i class="fas fa-times mr-1.5"></i>Desabilitado
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <span class="font-semibold text-gray-900">{{ $userCount }}</span> usuário(s)
                                            </td>
                                            <td class="w-28 px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="mx-auto grid w-[72px] grid-cols-2 items-center justify-items-center gap-2">
                                                    <a href="{{ route('cargos.edit', $role) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 transition-colors hover:bg-indigo-100" title="Editar cargo">
                                                        <i class="fas fa-pen text-xs"></i>
                                                    </a>
                                                    <form action="{{ route('cargos.destroy', $role) }}" method="POST" class="m-0 flex h-8 w-8 items-center justify-center" onsubmit="return confirm('Tem certeza que deseja excluir o cargo \'{{ $role->label }}\'?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-700 transition-colors hover:bg-red-100" title="Excluir cargo">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <div id="createRoleModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" aria-labelledby="createRoleModalTitle" role="dialog" aria-modal="true">
        <div id="createRoleModalBackdrop" class="absolute inset-0 bg-gray-900/45"></div>

        <div class="relative w-full max-w-xl overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 id="createRoleModalTitle" class="text-lg font-semibold text-gray-900">Novo cargo</h3>
                        <p class="mt-1 text-sm text-gray-500">Crie um cargo personalizado para organizar acessos e encaminhamentos.</p>
                    </div>
                    <button type="button" class="closeCreateRoleModal inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700" title="Fechar">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('cargos.store') }}" method="POST" class="px-6 py-5">
                @csrf

                @if($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <div class="mb-1 font-semibold">Revise os campos abaixo:</div>
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label for="create-role-label" class="mb-1 block text-sm font-medium text-gray-700">
                        Nome do cargo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="label" id="create-role-label" value="{{ old('label') }}" required class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-400 focus:ring-indigo-200" placeholder="Ex: Supervisor, Coordenador de Área...">
                    <p class="mt-1 text-xs text-gray-500">Este nome será exibido no sistema e nas listas de encaminhamento.</p>
                    @error('label')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="can_receive_forwardings" value="0">
                        <input type="checkbox" name="can_receive_forwardings" value="1" id="create-role-can-receive" {{ old('can_receive_forwardings', true) ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span>
                            <span class="block text-sm font-semibold text-gray-800">Pode receber encaminhamentos</span>
                            <span class="mt-1 block text-xs leading-5 text-gray-500">Usuários com este cargo aparecerão na lista de destinatários do CRADT ao encaminhar requerimentos.</span>
                        </span>
                    </label>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" class="closeCreateRoleModal inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold leading-none text-white shadow-sm transition-colors hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Criar cargo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('createRoleModal');
            const input = document.getElementById('create-role-label');
            const openButtons = [
                document.getElementById('openCreateRoleModalHeader'),
                document.getElementById('openCreateRoleModalEmpty'),
            ].filter(Boolean);
            const closeButtons = modal.querySelectorAll('.closeCreateRoleModal');
            const backdrop = document.getElementById('createRoleModalBackdrop');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                setTimeout(() => input?.focus(), 50);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            openButtons.forEach(button => button.addEventListener('click', openModal));
            closeButtons.forEach(button => button.addEventListener('click', closeModal));
            backdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            @if($errors->any() || session('openCreateRoleModal'))
                openModal();
            @endif
        });
    </script>
</x-app-diretor-geral-layout>
