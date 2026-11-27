<x-auth-shell title="SRE | Nova senha" photo-title="Quase lá, falta a senha"
    photo-text="Escolha uma senha nova e volte ao seu requerimento." :back-url="route('login')">

    <h1 class="text-3xl font-bold tracking-[-0.02em] text-floresta">Crie uma nova senha</h1>
    <p class="mt-2 text-base text-musgo">Use pelo menos 8 caracteres.</p>

    <form x-data="resetPasswordForm(@js($errors->messages()))" x-ref="form" @submit.prevent="submit"
        @input="clear($event.target.name)" method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-auth-field name="email" label="E-mail" type="email" :value="$request->email" autocomplete="username" />

        <x-auth-field name="password" label="Nova senha" type="password" placeholder="Crie uma senha" autocomplete="new-password" autofocus />

        <x-auth-field name="password_confirmation" label="Confirme sua nova senha" type="password" placeholder="Repita a senha" autocomplete="new-password" />

        <button type="submit" :disabled="submitting" x-text="submitting ? 'Salvando...' : 'Salvar nova senha'" class="auth-button w-full">
            {{ __('Salvar nova senha') }}
        </button>
    </form>
</x-auth-shell>
