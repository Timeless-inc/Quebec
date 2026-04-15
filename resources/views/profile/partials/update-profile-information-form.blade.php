<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Atualize as informações do perfil e o endereço de e-mail da sua conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="cpf" value="CPF" />
            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $user->cpf)" required autocomplete="cpf" />
            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
        </div>

        <div>
            <x-input-label for="rg" value="RG" />
            <x-text-input id="rg" name="rg" type="text" class="mt-1 block w-full" :value="old('rg', $user->rg)" required autocomplete="rg" />
            <x-input-error class="mt-2" :messages="$errors->get('rg')" />
        </div>

        <div>
            <x-input-label for="matricula" value="Matrícula" />
            <x-text-input id="matricula" name="matricula" type="text" class="mt-1 block w-full" :value="old('matricula', $user->matricula)" required autocomplete="matricula" />
            <x-input-error class="mt-2" :messages="$errors->get('matricula')" />
        </div>

        <div>
            <x-input-label for="second_matricula" value="Segunda Matrícula (Opcional)" />
            <x-text-input id="second_matricula" name="second_matricula" type="text" class="mt-1 block w-full" :value="old('second_matricula', $user->second_matricula)" autocomplete="second_matricula" />
            <x-input-error class="mt-2" :messages="$errors->get('second_matricula')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>
