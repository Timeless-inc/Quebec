<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('cradt') }}">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('cradt')" :active="request()->routeIs('cradt')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cradt-report')" :active="request()->routeIs('cradt-report')">
                        {{ __('Relatórios') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Usuários') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- User Actions -->
            <div x-data="{ openModal: false }" class="hidden sm:flex sm:items-center sm:ms-6">

            <!-- Botão verde com ícone (Trigger) -->
            <button @click="openModal = true" type="button" class="mr-4 w-10 h-10 bg-green-500 hover:bg-green-600 rounded-md transition-colors flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </button>

            <!-- Botão com o nome do usuário -->
            <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-gray-900 text-sm p-2 border border-gray-300 rounded-md shadow-sm">
                Olá, @<strong>{{ Auth::user()->username }}</strong>
            </a>            

            <!-- Botão "Sair" -->
            <form method="POST" action="{{ route('logout') }}" class="ml-4" style="margin-top: 6%; margin-left: 16px">
                @csrf
                <button type="submit" class="relative px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm font-medium text-sm transition duration-150 ease-in-out group">
                    <span class="group-hover:text-white group-hover:bg-red-500 inline-block w-full h-full absolute left-0 top-0 rounded-md"></span>
                    <span class="relative z-10 group-hover:text-white"><strong>{{ __('Sair') }}</strong></span>
                </button>
            </form>

                <!-- Modal Background -->
                <div x-show="openModal"
                    style="display: none;"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-40 bg-black bg-opacity-50">
                </div>

                <!-- Modal -->
                <div x-show="openModal"
                    style="display: none;"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    aria-labelledby="newCradtModalLabel"
                    role="dialog"
                    aria-modal="true"
                    @click.away="openModal = false">
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full">
                        <div class="flex justify-between items-center p-4 border-b border-gray-200">
                            <h5 class="text-lg font-medium text-gray-900" id="newCradtModalLabel">Pré-cadastro CRADT</h5>
                            <button @click="openModal = false" type="button" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <form id="cradtRegisterForm" method="POST" action="{{ route('cradt.register') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                                    <input type="text" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="cpf" name="cpf" required>
                                </div>
                                <div class="mb-4">
                                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
                                    <input type="text" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="matricula" name="matricula" required>
                                </div>
                            </form>
                        </div>
                        <div class="flex justify-end items-center p-4 bg-gray-50 border-t border-gray-200">
                            <button @click="openModal = false" type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Fechar
                            </button>
                            <button type="submit" form="cradtRegisterForm" class="ml-3 px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Pré-cadastrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>