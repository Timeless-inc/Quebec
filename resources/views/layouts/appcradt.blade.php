<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
            <meta name="user-id" content="{{ auth()->id() }}">
            <meta name="user-role" content="{{ auth()->user()->role }}">
        @endauth

        <title>{{ config('app.name', 'SRE') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/loading-spinner.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation-cradt')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- base de pop-up -->
        <div id="popup-notification" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: white; color: #333; padding: 16px 24px; border-radius: 8px; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 500; overflow: hidden; max-width: 90%; width: auto;">
            <span id="popup-message"></span>
            <div id="notification-timer" style="position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background-color: #4CAF50;"></div>
        </div>

        <!-- Importação do script de notificações -->
        <script src="{{ asset('js/notifications.js') }}"></script>
        
        <!-- Importação do script de Real-time -->
        <script src="{{ asset('js/requerimentos-realtime.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('notification'))
                    @if (is_array(session('notification')))
                        showTypedPopupNotification("{{ session('notification.message') }}", "{{ session('notification.type') }}");
                    @else
                        showTypedPopupNotification("{{ session('notification') }}", "success");
                    @endif
                @endif
            });
        </script>

        <!-- Loading Spinner -->
        <x-loading-spinner />

        <!-- Custom JavaScript -->
        <script src="{{ asset('js/form-loading.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
