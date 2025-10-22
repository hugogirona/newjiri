<aside class="w-64 bg-white border-r border-gray-200 flex flex-col min-h-screen">
    <!-- Logo / Brand -->
    <div class="flex items-center justify-between h-16 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="text-3xl font-bold text-indigo-600">{{ __('navigation.brand') }}</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <div class="space-y-1">
            <x-nav-link
                href="{{ route('dashboard') }}"
                :active="request()->routeIs('dashboard')"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'/></svg>'">
                {{ __('navigation.menu.dashboard') }}
            </x-nav-link>

            <x-nav-link
                href="{{ route('jiris.index') }}"
                :active="request()->routeIs('jiris.*')"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'/></svg>'">
                {{ __('navigation.menu.jiris') }}
            </x-nav-link>

            <x-nav-link
                href="{{ route('contacts.index') }}"
                :active="request()->routeIs('contacts.*')"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\'/></svg>'">
                {{ __('navigation.menu.contacts') }}
            </x-nav-link>

            <x-nav-link
                href="{{ route('projects.index') }}"
                :active="request()->routeIs('projects.*')"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z\'/></svg>'">
                {{ __('navigation.menu.projects') }}
            </x-nav-link>
        </div>
    </nav>

    <!-- User section -->
    @auth
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->first_name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->first_name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                    {{ __('navigation.user.logout') }}
                </button>
            </form>
        </div>
    @endauth
</aside>
