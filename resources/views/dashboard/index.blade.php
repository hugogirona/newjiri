<x-layout title="{{ __('dashboard.title') }} - Jiri">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('dashboard.title') }}</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('dashboard.welcome') }}, {{ auth()->user()->first_name }}
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ now()->isoFormat(__('dates.iso_format.long')) }}
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-6 max-w-7xl mx-auto">

        <!-- Statistiques principales - Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Jurys -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">{{ __('dashboard.stats.jiris') }}</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['jiris_count'] }}</p>
                    </div>
                </div>
                @if($stats['upcoming_jiris_count'] > 0)
                    <div class="mt-4 pt-4 border-t border-indigo-400">
                        <p class="text-sm text-indigo-100">
                            {{ __('dashboard.stats.upcoming', ['count' => $stats['upcoming_jiris_count']]) }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Contacts -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">{{ __('dashboard.stats.contacts') }}</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['contacts_count'] }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-blue-400 flex justify-between text-sm text-blue-100">
                    <span>{{ __('dashboard.stats.evaluators', ['count' => $stats['evaluators_count']]) }}</span>
                    <span>{{ __('dashboard.stats.evaluated', ['count' => $stats['evaluated_count']]) }}</span>
                </div>
            </div>

            <!-- Projets -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">{{ __('dashboard.stats.projects') }}</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['projects_count'] }}</p>
                    </div>
                </div>
                @if($stats['projects_count'] > 0)
                    <div class="mt-4 pt-4 border-t border-purple-400">
                        <p class="text-sm text-purple-100">
                            {{ __('dashboard.stats.implementations', ['count' => $stats['implementations_count']]) }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Activité récente -->
            <div class="bg-gradient-to-br from-slate-500 to-slate-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">{{ __('dashboard.stats.last_activity') }}</p>
                        <p class="text-lg font-semibold mt-2">
                            @if($stats['last_jiri'])
                                {{ $stats['last_jiri']->starts_at->diffForHumans() }}
                            @else
                                {{ __('dashboard.stats.none') }}
                            @endif
                        </p>
                    </div>
                </div>
                @if($stats['last_jiri'])
                    <div class="mt-4 pt-4 border-t border-slate-400">
                        <p class="text-sm text-green-100 truncate">
                            {{ $stats['last_jiri']->name }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bento Grid - Sections principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Jurys à venir - Large -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('dashboard.sections.upcoming_jiris') }}</h2>
                        <x-form.button size="sm" href="{{ route('jiris.create') }}">
                            {{ __('dashboard.actions.new_jiri') }}
                        </x-form.button>
                    </div>
                </div>
                <div class="p-6">
                    @if($upcomingJiris->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingJiris as $jiri)
                                <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition cursor-pointer"
                                     onclick="window.location='{{ route('jiris.show', $jiri) }}'">
                                    <div class="flex-shrink-0">
                                        <div class="bg-indigo-100 rounded-lg p-3 text-center min-w-[60px]">
                                            <div class="text-2xl font-bold text-indigo-600">
                                                {{ $jiri->starts_at->format('d') }}
                                            </div>
                                            <div class="text-xs text-indigo-600 uppercase">
                                                {{ $jiri->starts_at->format('M') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900">{{ $jiri->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1"  stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $jiri->starts_at->format('H:i') }}
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1"  stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $jiri->location }}
                                            </span>
                                        </p>
                                        <div class="flex gap-4 mt-2 text-xs text-gray-500">
                                            <span>{{ __('dashboard.stats.participants', ['count' => $jiri->contacts_count]) }}</span>
                                            <span>{{ __('dashboard.stats.projects', ['count' => $jiri->projects_count]) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $jiri->starts_at->translatedFormat(__('dates.format.medium')) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($stats['upcoming_jiris_count'] > 3)
                            <div class="mt-4 text-center">
                                <x-form.button variant="ghost" size="sm" href="{{ route('jiris.index') }}">
                                    {{ __('dashboard.actions.view_all_jiris', ['count' => $stats['upcoming_jiris_count']]) }}
                                </x-form.button>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">{{ __('dashboard.empty.no_upcoming_jiris') }}</p>
                            <x-form.button size="sm" href="{{ route('jiris.create') }}" class="mt-4">
                                {{ __('dashboard.actions.create_jiri') }}
                            </x-form.button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('dashboard.sections.quick_actions') }}</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('jiris.create') }}"
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition group">
                        <div class="bg-indigo-100 group-hover:bg-indigo-200 rounded-lg p-2">
                            <svg class="w-6 h-6 text-indigo-600" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">{{ __('dashboard.actions.create_jiri') }}</p>
                            <p class="text-sm text-gray-500">{{ __('dashboard.actions.organize_session') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('contacts.create') }}"
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                        <div class="bg-blue-100 group-hover:bg-blue-200 rounded-lg p-2">
                            <svg class="w-6 h-6 text-blue-600" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">{{ __('dashboard.actions.add_contact') }}</p>
                            <p class="text-sm text-gray-500">{{ __('dashboard.actions.new_participant') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('projects.create') }}"
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                        <div class="bg-purple-100 group-hover:bg-purple-200 rounded-lg p-2">
                            <svg class="w-6 h-6 text-purple-600" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">{{ __('dashboard.actions.create_project') }}</p>
                            <p class="text-sm text-gray-500">{{ __('dashboard.actions.new_project') }}</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Bento Grid - Sections secondaires -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Contacts récents -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('dashboard.sections.recent_contacts') }}</h2>
                    <a href="{{ route('contacts.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                        {{ __('dashboard.actions.view_all') }}
                    </a>
                </div>
                <div class="p-6">
                    @if($recentContacts->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentContacts as $contact)
                                <a href="{{ route('contacts.show', $contact) }}"
                                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition">
                                    @if($contact->avatar)
                                        <img src="{{ $contact->avatar_url }}"
                                             alt="Avatar"
                                             class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                            {{ substr($contact->first_name, 0, 1) }}{{ substr($contact->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $contact->first_name }} {{ $contact->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $contact->email }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">{{ __('dashboard.empty.no_contacts') }}</p>
                    @endif
                </div>
            </div>

            <!-- Projets récents -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('dashboard.sections.recent_projects') }}</h2>
                    <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                        {{ __('dashboard.actions.view_all') }}
                    </a>
                </div>
                <div class="p-6">
                    @if($recentProjects->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentProjects as $project)
                                <a href="{{ route('projects.show', $project) }}"
                                   class="block p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition">
                                    <p class="text-sm font-medium text-gray-900">{{ $project->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        {{ $project->description ?? __('dashboard.empty.no_description') }}
                                    </p>
                                    <div class="flex gap-2 mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ __('dashboard.stats.jiris_count', ['count' => $project->jiris_count]) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">{{ __('dashboard.empty.no_projects') }}</p>
                    @endif
                </div>
            </div>

            <!-- Activité -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('dashboard.sections.activity') }}</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($stats['jiris_count'] > 0)
                            <div class="flex items-start gap-3">
                                <div class="bg-indigo-100 rounded-full p-2">
                                    <svg class="w-4 h-4 text-indigo-600" f viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('dashboard.stats.jiris_count', ['count' => $stats['jiris_count']]) }}</p>
                                    <p class="text-xs text-gray-500">{{ __('dashboard.activity.total') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($stats['contacts_count'] > 0)
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-4 h-4 text-blue-600"  viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('dashboard.stats.participants_count', ['count' => $stats['contacts_count']]) }}</p>
                                    <p class="text-xs text-gray-500">{{ __('dashboard.activity.in_network') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($stats['projects_count'] > 0)
                            <div class="flex items-start gap-3">
                                <div class="bg-purple-100 rounded-full p-2">
                                    <svg class="w-4 h-4 text-purple-600"  viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('dashboard.stats.projects_count', ['count' => $stats['projects_count']]) }}</p>
                                    <p class="text-xs text-gray-500">{{ __('dashboard.activity.to_evaluate') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($stats['implementations_count'] > 0)
                            <div class="flex items-start gap-3">
                                <div class="bg-slate-100 rounded-full p-2">
                                    <svg class="w-4 h-4 text-slate-600"  viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('dashboard.stats.implementations', ['count' => $stats['implementations_count']]) }}</p>
                                    <p class="text-xs text-gray-500">{{ __('dashboard.activity.completed') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-layout>
