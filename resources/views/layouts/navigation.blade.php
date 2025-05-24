<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ in_array(Auth::user()->role, ['Cradt', 'Manager']) ? url('/cradt/dashboard') : url('/aluno/dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>

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

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Botão de Notificações -->
                <div id="notification-icon" class="relative mr-2">
                    <button
                        type="button"
                        onclick="toggleNotifications('notification-list')"
                        id="notification-button"
                        class="text-sm p-2 px-3 border border-gray-300 rounded-md shadow-sm flex items-center">
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
                    <div id="notification-list" class="absolute right-0 mt-2 bg-white border border-gray-300 rounded-md shadow-lg w-64 z-50" style="display: none;">
                        <ul id="notifications" class="p-2 text-sm text-green-700 max-h-80 overflow-y-auto"></ul>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="ml-3 relative">
                    <div>
                        <button @click="open = !open" type="button" class="text-gray-700 hover:text-gray-900 text-sm p-2 border border-gray-300 rounded-md shadow-sm flex items-center">
                            Olá, <strong class="ml-1">{{ Auth::user()->username }}</strong>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-md shadow-xl z-50 border border-gray-200"
                        style="display: none;">

                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Perfil') }}
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 border-l-4 border-transparent hover:border-red-500 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="group-hover:text-red-500">{{ __('Sair') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="flex items-center sm:hidden">
                <!-- Botão de Notificações Mobile -->
                <div id="mobile-notification-icon" class="relative mr-3">
                    <button
                        type="button"
                        onclick="toggleNotificationsMobile()"
                        class="text-sm p-2 border border-gray-300 rounded-md shadow-sm flex items-center">
                        <!-- Ícone do Sino -->
                        <span class="text-orange-500">
                            🔔
                        </span>
                        <!-- Contador de Notificações Mobile -->
                        <span id="mobile-notification-count" class="absolute top-0 right-0 text-xs text-white bg-red-500 rounded-full px-1 font-bold" style="display: none;">
                            0
                        </span>
                    </button>
                    <!-- Lista de Notificações Mobile -->
                    <div id="mobile-notification-list" class="fixed inset-0 pt-16 px-4 pb-4 bg-gray-800 bg-opacity-75 z-50" style="display: none;">
                        <div class="bg-white rounded-lg shadow-xl max-w-md mx-auto overflow-hidden">
                            <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Notificações</h3>
                                <button type="button" onclick="toggleNotificationsMobile()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4">
                                <ul id="mobile-notifications" class="text-sm text-green-700 max-h-80 overflow-y-auto"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botão Hamburguer -->
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Responsivo (visível apenas quando o hamburguer é clicado) -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1 border-t border-gray-200">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->role === 'Cradt')
            <x-responsive-nav-link :href="route('cradt-report')" :active="request()->routeIs('cradt-report')" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ __('Relatórios') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Opções de Perfil e Logout (Mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4 py-2">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? Auth::user()->username }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link 
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleNotifications(elementId) {
        const notificationList = document.getElementById(elementId);
        if (notificationList) {
            if (notificationList.style.display === 'none') {
                document.querySelectorAll('#notification-list, #mobile-notification-list').forEach(el => {
                    if (el.id !== elementId) el.style.display = 'none';
                });
                
                notificationList.style.display = 'block';
                
                loadNotifications(elementId === 'mobile-notification-list' ? 'mobile-notifications' : 'notifications');
            } else {
                notificationList.style.display = 'none';
            }
        }
    }
    
    function toggleNotificationsMobile() {
        const mobileNotificationList = document.getElementById('mobile-notification-list');
        const body = document.body;
        
        if (mobileNotificationList) {
            if (mobileNotificationList.style.display === 'none') {
                document.getElementById('notification-list').style.display = 'none';
                
                mobileNotificationList.style.display = 'block';
                body.classList.add('overflow-hidden'); 
                loadNotifications('mobile-notifications');
            } else {
                mobileNotificationList.style.display = 'none';
                body.classList.remove('overflow-hidden');
            }
        }
    }
    
    function loadNotifications(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = '<li class="text-center py-2">Carregando notificações...</li>';
        
        fetch('/notifications')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<li class="text-center py-4 text-gray-500 italic">Você não tem notificações no momento.</li>';
                    return;
                }
                
                container.innerHTML = '';
                data.forEach(notification => {
                    const li = document.createElement('li');
                    li.className = notification.is_read 
                        ? 'p-3 border-b border-gray-200 text-gray-500' 
                        : 'p-3 border-b border-gray-200 text-gray-700 bg-blue-50';
                    li.setAttribute('data-notification-id', notification.id);
                    
                    li.innerHTML = `
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">${notification.title}</span>
                            <span class="text-xs status-indicator ${notification.is_read ? 'text-gray-500' : 'text-green-700 font-bold'}">${notification.is_read ? 'Lida' : 'Nova'}</span>
                        </div>
                        <div class="text-sm mt-1">${notification.message}</div>
                    `;
                    
                    if (!notification.is_read) {
                        li.style.cursor = 'pointer';
                        li.onclick = () => markAsRead(notification.id, containerId);
                    }
                    
                    container.appendChild(li);
                });
                
                updateNotificationCounter(data.filter(n => !n.is_read).length);
            })
            .catch(error => {
                console.error('Erro ao carregar notificações:', error);
                container.innerHTML = '<li class="text-center py-2 text-red-500">Erro ao carregar notificações</li>';
            });
    }
    
    function markAsRead(id, containerId) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao marcar notificação como lida');
            }
            return response.json();
        })
        .then(data => {
            console.log('Notificação marcada como lida:', data.message);
            
            loadNotifications(containerId);
        })
        .catch(error => {
            console.error('Erro ao marcar notificação como lida:', error);
        });
    }
    
    function updateNotificationCounter(count) {
        const desktopCount = document.getElementById('notification-count');
        const mobileCount = document.getElementById('mobile-notification-count');
        
        if (desktopCount) {
            if (count > 0) {
                desktopCount.textContent = count;
                desktopCount.style.display = 'block';
            } else {
                desktopCount.style.display = 'none';
            }
        }
        
        if (mobileCount) {
            if (count > 0) {
                mobileCount.textContent = count;
                mobileCount.style.display = 'block';
            } else {
                mobileCount.style.display = 'none';
            }
        }
    }
    
    document.addEventListener('click', function(event) {
        const notificationButton = document.getElementById('notification-button');
        const mobileNotificationButton = document.querySelector('#mobile-notification-icon button');
        const notificationList = document.getElementById('notification-list');
        const mobileNotificationList = document.getElementById('mobile-notification-list');
        
        if (notificationButton && notificationList && 
            !notificationButton.contains(event.target) && 
            !notificationList.contains(event.target)) {
            notificationList.style.display = 'none';
        }
        
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetch('/notifications/count')
            .then(response => response.json())
            .then(data => {
                updateNotificationCounter(data.count);
            })
            .catch(error => {
                console.error('Erro ao carregar contagem de notificações:', error);
            });
        
        const notificationList = document.getElementById('notification-list');
        const mobileNotificationList = document.getElementById('mobile-notification-list');
        
        if (notificationList) notificationList.style.display = 'none';
        if (mobileNotificationList) mobileNotificationList.style.display = 'none';
    });
</script>