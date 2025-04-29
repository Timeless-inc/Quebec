<title>SRE - Perfil</title>
<x-app-layout>    
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <!-- Formulário de Edição -->
                <div>
                    <h3 class="text-xl font-semibold">Editar Perfil</h3>

                    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Nome Completo -->
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nome Completo</label>
                                <input id="name" name="name" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->name }}" required>
                            </div>

                            <!-- Matrícula -->
                            <div>
                                <label for="matricula" class="block font-medium text-sm text-gray-700">Matrícula</label>
                                <input id="matricula" name="matricula" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->matricula }}">
                            </div>

                            <!-- CPF -->
                            <div>
                                <label for="cpf" class="block font-medium text-sm text-gray-700">CPF</label>
                                <input id="cpf" name="cpf" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->cpf }}">
                            </div>

                            <!-- RG -->
                            <div>
                                <label for="rg" class="block font-medium text-sm text-gray-700">RG</label>
                                <input id="rg" name="rg" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->rg }}">
                            </div>
                        </div>

                        <!-- Botão de Atualização -->
                        <div class="mt-6">
                            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md">Atualizar Informações</button>
                        </div>
                    </form>

                    <p class="mt-4 text-gray-500 text-sm">Membro desde: <strong>{{ Auth::user()->created_at->format('d F Y') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>