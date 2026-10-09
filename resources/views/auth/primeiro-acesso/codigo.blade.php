<x-primeiro-acesso step="2" title="Confirme seu e-mail" subtitle="Enviamos um código de 6 dígitos para {{ $emailMascarado }}. Ele vale por {{ $minutos }} minutos.">
    @if(session('status'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('primeiro-acesso.verificar') }}" class="space-y-4">
        @csrf

        <div>
            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
            <input id="codigo" type="text" name="codigo" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000"
                class="py-3 px-3 bg-gray-50 border border-gray-300 text-gray-900 text-2xl tracking-[0.5em] text-center rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full">
            <x-input-error :messages="$errors->get('codigo')" class="mt-1 text-xs" />
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('register') }}" class="text-sm text-emerald-600 hover:text-emerald-800">Voltar</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg shadow-md transition-colors">
                Confirmar código
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('primeiro-acesso.reenviar') }}" class="mt-6 border-t border-gray-100 pt-4 text-sm text-gray-500">
        @csrf
        Não recebeu? Confira a caixa de spam ou
        <button type="submit" class="font-medium text-emerald-600 hover:text-emerald-800">envie um novo código</button>.
    </form>
</x-primeiro-acesso>
