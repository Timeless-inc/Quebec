<x-auth-shell title="SRE | Confirmar e-mail" photo-title="Confirme seu e-mail"
    photo-text="Falta um passo para ter acesso ao sistema.">

    <h1 class="text-3xl font-bold tracking-[-0.02em] text-floresta">Confirme seu e-mail</h1>
    <p class="mt-2 text-base text-musgo">Enviamos um link para o e-mail do seu cadastro. Clique nele para começar. Não chegou? Peça outro.</p>

    @if (session('status') == 'verification-link-sent')
        <p class="mt-6 text-sm font-semibold text-esmeralda" role="status">Enviamos um novo link para o e-mail do seu cadastro.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
        @csrf
        <button type="submit" class="auth-button w-full">{{ __('Enviar outro link') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
        @csrf
        <button type="submit" class="auth-link px-1 text-sm">{{ __('Sair') }}</button>
    </form>
</x-auth-shell>
