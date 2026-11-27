<x-auth-shell title="SRE | Confirmar senha" photo-title="Área protegida"
    photo-text="Confirme sua senha para continuar.">

    <h1 class="text-3xl font-bold tracking-[-0.02em] text-floresta">Confirme sua senha</h1>
    <p class="mt-2 text-base text-musgo">Esta é uma área protegida. Digite sua senha para continuar.</p>

    <form x-data="confirmPasswordForm(@js($errors->messages()))" x-ref="form" @submit.prevent="submit"
        @input="clear($event.target.name)" method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <x-auth-field name="password" label="Senha" type="password" placeholder="Sua senha" autocomplete="current-password" autofocus />

        <button type="submit" :disabled="submitting" x-text="submitting ? 'Confirmando...' : 'Confirmar'" class="auth-button w-full">
            {{ __('Confirmar') }}
        </button>
    </form>
</x-auth-shell>
