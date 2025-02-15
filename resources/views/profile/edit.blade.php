<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 flex gap-8">

                <!-- Sidebar - Foto de Perfil -->
                <div class="w-1/3 text-center border-r pr-6">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://via.placeholder.com/150' }}"
                        alt="Profile Photo" class="w-32 h-32 rounded-full mx-auto">

                    <form method="POST" action="{{ route('profile.update-photo') }}" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <label class="block">
                            <span class="sr-only">+ Adicionar Foto</span>
                            <input type="file" name="profile_photo" class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm
                                   file:font-semibold file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100">
                        </label>
                        <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-md">+ Adicionar Foto</button>
                    </form>

                    <p class="mt-4 text-gray-500 text-sm">O tamanho máximo de upload é <strong>5 MB</strong></p>
                    <p class="text-gray-500 text-sm">Membro desde:<strong>{{ Auth::user()->created_at->format('d F Y') }}</strong></p>
                </div>

                <!-- Formulário de Edição -->
                <div class="w-2/3">
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

                            <!-- Órgão Expedidor -->
                            <div>
                                <label for="orgao_expedidor" class="block font-medium text-sm text-gray-700">Órgão Expedidor</label>
                                <input id="orgao_expedidor" name="orgao_expedidor" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->orgao_expedidor }}">
                            </div>

                            <!-- Telefone -->
                            <div>
                                <label for="telefone" class="block font-medium text-sm text-gray-700">Número de Telefone</label>
                                <input id="telefone" name="telefone" type="text" class="block w-full border rounded-md px-3 py-2"
                                    value="{{ Auth::user()->telefone }}">
                            </div>

                            <!-- Botão de Atualização -->
                            <div class="mt-6">
                                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md">Atualizar Informações</button>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>