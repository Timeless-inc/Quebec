<x-primeiro-acesso step="1" title="Primeiro acesso" subtitle="Informe o CPF e a matrícula cadastrados pela CRADT. Enviaremos um código para o seu e-mail institucional.">
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
            <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required autofocus inputmode="numeric" maxlength="14" placeholder="000.000.000-00"
                class="py-3 px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
            <x-input-error :messages="$errors->get('cpf')" class="mt-1 text-xs" />
        </div>

        <div>
            <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
            <input id="matricula" type="text" name="matricula" value="{{ old('matricula') }}" required placeholder="2025IFPEI0001"
                class="py-3 px-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
            <x-input-error :messages="$errors->get('matricula')" class="mt-1 text-xs" />
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('login') }}" class="text-sm text-emerald-600 hover:text-emerald-800">Já possui uma conta?</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg shadow-md transition-colors">
                Enviar código
            </button>
        </div>
    </form>
</x-primeiro-acesso>
