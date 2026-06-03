<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="upload-limits" content='{{ json_encode(array(
            "image" => config("validation.file_limits.image_upload_max_kb"),
            "pdf" => config("validation.file_limits.pdf"),
            "total" => config("validation.file_limits.total_per_request_kb"),
            "image_target_max_width" => config("validation.file_limits.image_target_max_width"),
            "client_warning_kb" => config("validation.file_limits.client_warning_kb"),
            "image_max_pixels" => config("validation.file_limits.image_max_pixels"),
            "client_max_pixels" => config("validation.file_limits.client_max_pixels"),
            "client_compress_trigger_kb" => config("validation.file_limits.client_compress_trigger_kb")
        )) }}'>
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
        <style>
            .modal-content {
                background-color: #fff;
            }
            .modal-body {
                background-color: #fff;
            }
            .bg-emerald-700 { background-color: #047857 !important; }
            .bg-red-700 { background-color: #b91c1c !important; }
            .bg-amber-600 { background-color: #d97706 !important; }
            .bg-indigo-700 { background-color: #4338ca !important; }
            .bg-purple-600 { background-color: #9333ea !important; }
            .bg-gray-600 { background-color: #4b5563 !important; }
            .text-white { color: #fff !important; }
            .text-emerald-100 { color: #d1fae5 !important; }
            .text-red-100 { color: #fee2e2 !important; }
            .text-amber-50 { color: #fffbeb !important; }
            .text-indigo-100 { color: #e0e7ff !important; }
            .text-purple-100 { color: #f3e8ff !important; }
            .text-purple-600 { color: #9333ea !important; }
            .text-purple-800 { color: #6b21a8 !important; }
            .bg-purple-50 { background-color: #faf5ff !important; }
            .border-purple-100 { border-color: #f3e8ff !important; }
            .bg-gradient-to-r.from-emerald-600.via-emerald-700.to-green-700,
            .bg-gradient-to-r.from-emerald-600.to-emerald-700 { background: #047857 !important; }
            .bg-gradient-to-r.from-red-600.via-red-700.to-pink-700,
            .bg-gradient-to-r.from-red-600.to-red-700,
            .bg-gradient-to-r.from-red-600.to-rose-600 { background: #b91c1c !important; }
            .bg-gradient-to-r.from-pink-600.via-pink-700.to-rose-700,
            .bg-gradient-to-r.from-pink-600.to-pink-700 { background: #be185d !important; }
            .bg-gradient-to-r.from-amber-500.to-orange-600 { background: #d97706 !important; }
            .bg-gradient-to-r.from-purple-600.to-indigo-600 { background: #9333ea !important; }
            .bg-gradient-to-r.from-gray-500.to-gray-600 { background: #4b5563 !important; }
        </style>
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
