@php
    $isDiretorGeral = auth()->user()->isDiretorGeral();
    $layoutComponent = $isDiretorGeral ? 'app-diretor-geral-layout' : 'app-cradt';
    $indexRoute = $isDiretorGeral ? route('diretor-geral.users.index') : route('users.index');
    $fieldClass = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-400 focus:ring focus:ring-emerald-200 focus:ring-opacity-50';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">Pré-cadastro de usuário</h2>
            <p class="mt-1 text-sm text-gray-500">
                Informe os dados essenciais. No primeiro acesso a pessoa confirma o e-mail com um código e preenche o restante (senha, RG, data de nascimento e telefone).
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md">
                <form method="POST" action="{{ route('cradt.register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Tipo de usuário</label>
                        <select id="role" name="role" class="{{ $fieldClass }}">
                            <option value="Aluno" @selected(old('role', 'Aluno') === 'Aluno')>Aluno</option>
                            <option value="Cradt" @selected(old('role') === 'Cradt')>Servidor da CRADT</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="{{ $fieldClass }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail institucional</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nome@discente.ifpe.edu.br" class="{{ $fieldClass }}">
                        <p class="mt-1 text-xs text-gray-500">O código de primeiro acesso será enviado para este endereço.</p>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                            <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required inputmode="numeric" maxlength="14" placeholder="000.000.000-00" class="{{ $fieldClass }}">
                            @error('cpf')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula principal</label>
                            <input id="matricula" type="text" name="matricula" value="{{ old('matricula') }}" required class="{{ $fieldClass }}">
                            @error('matricula')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-700 mb-1">
                            Matrículas adicionais
                            <span class="text-xs text-gray-500">(opcional)</span>
                        </span>
                        <x-matriculas-adicionais />
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                        <a href="{{ $indexRoute }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 no-underline shadow-sm hover:bg-gray-50">
                            Voltar
                        </a>
                        <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                            Pré-cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dynamic-component>
