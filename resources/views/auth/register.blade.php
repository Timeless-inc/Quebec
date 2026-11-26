<x-auth-shell title="SRE | Cadastro" photo-title="Crie sua conta"
    photo-text="Preencha seus dados para ter acesso ao sistema.">

    <div x-data="registerForm(@js($errors->messages()), @js((bool) old('has_second_matricula')))"
        @input="clear($event.target.name)" @change="clear($event.target.name)">

        <h1 class="text-3xl font-semibold tracking-tight text-gray-900">Cadastre-se</h1>

        <x-auth-session-status class="mt-4" :status="session('status')" />

        <div class="mt-6">
            <ol class="flex gap-2" aria-label="Etapas do cadastro">
                <template x-for="(item, index) in steps" :key="item.title">
                    <li class="flex-1">
                        <button type="button" @click="goTo(index)" :title="item.title" :aria-current="index === step ? 'step' : null"
                            :aria-label="item.title + (stepHasError(index) ? ' (com erros)' : '')"
                            class="group block w-full rounded-full py-2 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30">
                            <span class="block h-1.5 overflow-hidden rounded-full bg-gray-200 transition-colors group-hover:bg-gray-300">
                                <span class="block h-full rounded-full motion-safe:transition-[width] motion-safe:duration-500 motion-safe:ease-out-expo"
                                    :class="stepHasError(index) ? 'bg-red-600' : 'bg-emerald-600'"
                                    :style="{ width: index <= step || stepHasError(index) ? '100%' : '0%' }"></span>
                            </span>
                        </button>
                    </li>
                </template>
            </ol>
            <p class="mt-1 text-sm text-gray-600">
                Etapa <span x-text="step + 1"></span> de <span x-text="steps.length"></span>:
                <span class="font-semibold text-gray-900" x-text="steps[step].title"></span>
            </p>
        </div>

        <form x-ref="form" @submit.prevent="next" method="POST" action="{{ route('register') }}" class="mt-6" novalidate>
            @csrf

            <div x-cloak x-show="step === 0" x-transition:enter="motion-safe:transition motion-safe:duration-300 motion-safe:ease-out-expo"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-5">
                <x-auth-field name="username" label="Nome de usuário" placeholder="maria.silva" autocomplete="username" autofocus />
                <x-auth-field name="name" label="Nome completo" placeholder="Nome como consta na matrícula" autocomplete="name" />
                <x-auth-field name="email" label="E-mail" type="email" placeholder="seuemail@discente.ifpe.edu.br" autocomplete="email" />
            </div>

            <div x-cloak x-show="step === 1" x-transition:enter="motion-safe:transition motion-safe:duration-300 motion-safe:ease-out-expo"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-5">
                <x-auth-field name="matricula" label="Matrícula" placeholder="2025IFPEI0001" />

                <label for="has_second_matricula" class="-my-2 inline-flex cursor-pointer items-center gap-2.5 py-2 text-sm text-gray-700">
                    <input id="has_second_matricula" name="has_second_matricula" type="checkbox" :checked="hasSecondMatricula"
                        @change="toggleSecondMatricula($event.target.checked)"
                        class="size-4 rounded border-gray-400 text-emerald-700 focus:ring-emerald-500">
                    {{ __('Possuo uma segunda matrícula') }}
                </label>

                <div x-cloak x-show="hasSecondMatricula">
                    <x-auth-field name="second_matricula" label="Segunda matrícula" placeholder="2025IFPEI0002" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2 sm:gap-3">
                    <div>
                        <label for="rg_uf" class="block text-sm font-medium text-gray-800">UF do RG</label>
                        <select id="rg_uf" name="rg_uf" class="auth-input mt-1.5" :aria-invalid="errors.rg_uf ? 'true' : 'false'"
                            aria-describedby="rg_uf-message">
                            <option value="">UF</option>
                            @foreach ($states as $code => $name)
                                <option value="{{ $code }}" @selected(old('rg_uf') === $code)>{{ $code }}, {{ $name }}</option>
                            @endforeach
                        </select>
                        <p id="rg_uf-message" x-cloak x-show="errors.rg_uf" x-text="errors.rg_uf" aria-live="polite"
                            class="mt-1.5 text-sm font-medium text-red-700"></p>
                    </div>
                    <x-auth-field name="rg" label="RG ou CIN" placeholder="Número do RG ou CIN" autocomplete="off"
                        maxlength="11" @input="maskRg($event)" />
                </div>

                <x-auth-field name="cpf" label="CPF" placeholder="000.000.000-00" inputmode="numeric" autocomplete="off"
                    @input="maskCpf($event)" />
            </div>

            <div x-cloak x-show="step === 2" x-transition:enter="motion-safe:transition motion-safe:duration-300 motion-safe:ease-out-expo"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-5">
                <x-auth-field name="password" label="Senha" type="password" placeholder="Crie uma senha"
                    hint="Use pelo menos 8 caracteres." autocomplete="new-password" />
                <x-auth-field name="password_confirmation" label="Confirme sua senha" type="password"
                    placeholder="Repita a senha" autocomplete="new-password" />
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" x-cloak x-show="step > 0" @click="goTo(step - 1)" class="auth-button-secondary">Voltar</button>
                <button type="submit" :disabled="submitting" x-text="isLastStep ? (submitting ? 'Criando conta...' : 'Criar conta') : 'Continuar'"
                    class="auth-button flex-1">{{ __('Continuar') }}</button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            {{ __('Já possui uma conta?') }}
            <a href="{{ route('login') }}" class="auth-link px-1">Entrar</a>
        </p>
    </div>
</x-auth-shell>
