<!doctype html>
<html lang="{{ App::getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Jiri - Gestion de Jurys' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    {{ $head ?? '' }}
</head>
<body class="h-full bg-gray-50">
<div class="flex h-full">
    @auth
        <!-- Navbar Desktop - fixe à gauche -->
        <div class="hidden lg:block lg:w-64 lg:flex-shrink-0">
            <x-navigation.navbar />
        </div>
    @endauth

    <div class="flex-1 flex flex-col overflow-auto">
        @auth
            <!-- Mobile menu button -->
            <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <span class="text-xl font-bold text-indigo-600">{{__('navigation.brand')}}</span>
                <button type="button" id="mobile-menu-button" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu" class="hidden fixed inset-0 z-50 lg:hidden">
                <div class="fixed inset-0 bg-white bg-opacity-75" id="mobile-menu-overlay"></div>
                <div class="fixed inset-y-0 left-0 w-64 bg-white" id="mobile-menu-content">
                    <x-navigation.navbar />
                </div>
            </div>
        @endauth


        @if(isset($header))
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10 flex-shrink-0">
                {{ $header }}
            </header>
            @endif

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        @if(isset($footer))
            <footer class="bg-white border-t border-gray-200 flex-shrink-0">
                <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
                    <p>&copy; {{now()->format('Y')}} HEPL</p>
                </div>
            </footer>
        @endif
    </div>
</div>
@stack('scripts')

<!-- Script pour le menu mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuOverlay = document.getElementById('mobile-menu-overlay');

            if (menuButton && mobileMenu) {
                // Ouvrir le menu
                menuButton.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                });

                // Fermer le menu en cliquant sur l'overlay
                menuOverlay?.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });

                // Fermer le menu avec la touche Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
