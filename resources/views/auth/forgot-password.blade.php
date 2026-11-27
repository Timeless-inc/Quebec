<x-auth-shell title="SRE | Recuperar senha" photo-title="Sem problema, é rápido"
    photo-text="Informe seu e-mail e enviamos o caminho para criar uma nova senha." :back-url="route('login')">

    <h1 class="text-3xl font-bold tracking-[-0.02em] text-floresta">Esqueceu sua senha?</h1>
    <p class="mt-2 text-base text-musgo">Informe seu e-mail. Enviaremos um link para você criar uma nova senha.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form x-data="forgotPasswordForm(@js($errors->messages()))" x-ref="form" @submit.prevent="submit"
        @input="clear($event.target.name)" method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <x-auth-field name="email" label="E-mail" type="email" placeholder="seuemail@discente.ifpe.edu.br" autocomplete="username" autofocus />

        <button type="submit" :disabled="submitting" x-text="submitting ? 'Enviando...' : 'Enviar link'" class="auth-button w-full">
            {{ __('Enviar link') }}
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-musgo">
        Lembrou a senha?
        <a href="{{ route('login') }}" class="auth-link px-1">Entrar</a>
    </p>
</x-auth-shell>
