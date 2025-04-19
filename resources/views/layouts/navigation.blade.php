<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'Cradt' ? url('/cradt/dashboard') : url('/aluno/dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>


                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'Cradt')
                    <x-nav-link :href="route('cradt-report')" :active="request()->routeIs('cradt-report')">
                        {{ __('Relatórios') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Actions -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            <!-- Botão de Notificações -->
            <div id="notification-icon" class="relative mr-4">
                <button 
                    onclick="toggleNotifications()" 
                    id="notification-button" 
                    class="relative px-3 py-1 text-gray-700 hover:text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm">
                        <!-- Ícone do Sino -->
                        <span id="notification-icon-bell" class="text-orange-500">
                        🔔
                        </span>
                        <!-- Contador de Notificações -->
                        <span id="notification-count" class="absolute top-0 right-0 text-xs text-white bg-red-500 rounded-full px-1 font-bold" style="display: none;">
                        0
                        </span>
                </button>
                <!-- Lista de Notificações -->
                    <div id="notification-list" class="absolute right-0 mt-2 bg-white border border-gray-300 rounded-md shadow-lg w-64" style="display: none;">
                        <ul id="notifications" class="p-2 text-sm text-green-700 max-h-80 overflow-y-auto"></ul>
                    </div>
            </div>
                <!-- Botão com o nome do usuário -->
                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-gray-900 text-sm p-2 border border-gray-300 rounded-md shadow-sm">
                    Olá, @<strong>{{ Auth::user()->username }}</strong>
                </a>

            <!-- Botão "Sair" -->
            <form method="POST" action="{{ route('logout') }}" class="ml-4" style=" margin-top: 8.5%; margin-left: 55px">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-gray-900 font-medium text-sm">
                        <strong>{{ __('Sair') }}</strong>
                    </button>
                </form>
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
            @if(Auth::user()->role === 'Cradt')
            <x-nav-link :href="route('cradt-report')" :active="request()->routeIs('cradt-report')">
                {{ __('Relatórios') }}
            </x-nav-link>
            @endif
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