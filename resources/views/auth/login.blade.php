<x-auth-shell title="SRE | Login" photo-title="Bem-vindo de volta!"
    photo-text="Para fazer seus requerimentos, faça login com suas informações.">

    <h1 class="text-3xl font-bold tracking-[-0.02em] text-floresta">Acesse sua conta</h1>
    <p class="mt-2 text-base text-musgo">Entre com seu e-mail e sua senha.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form x-data="loginForm(@js($errors->messages()))" x-ref="form" @submit.prevent="submit"
        @input="clear($event.target.name)" method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <x-auth-field name="email" label="E-mail" type="email" placeholder="seuemail@discente.ifpe.edu.br" autocomplete="username" autofocus />

        <x-auth-field name="password" label="Senha" type="password" placeholder="Sua senha" autocomplete="current-password" />

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="-my-2 inline-flex cursor-pointer items-center gap-2.5 py-2 text-sm text-tinta">
                <input id="remember_me" name="remember" type="checkbox" class="size-4 rounded border-musgo/60 text-esmeralda focus:ring-campus">
                {{ __('Lembrar senha') }}
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link -my-2 px-1 py-2 text-sm">{{ __('Esqueceu sua senha?') }}</a>
            @endif
        </div>

        <button type="submit" :disabled="submitting" x-text="submitting ? 'Entrando...' : 'Entrar'" class="auth-button w-full">
            {{ __('Entrar') }}
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-musgo">
        Não tem conta?
        <a href="{{ route('register') }}" class="auth-link px-1">Cadastre-se</a>
    </p>
</x-auth-shell>
