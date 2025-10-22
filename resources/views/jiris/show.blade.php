<x-layout :title="__('jiris.title', ['name' => $jiri->name])">

    <x-slot:header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('jiris.index') }}">
                        {{ __('jiri.actions.back') }}
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $jiri->name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('jiris.edit', $jiri) }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        {{ __('jiri.actions.edit') }}
                    </a>
                    <form action="{{ route('jiris.destroy', $jiri) }}" method="POST"
                          onsubmit="return confirm('{{ __('jiri.confirm.delete') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            {{ __('jiri.actions.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot:header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Informations générales -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('jiri.sections.general_info') }}</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('jiri.fields.date_time') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $jiri->starts_at->isoFormat(__('dates.iso_format.long')) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('jiri.fields.location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jiri->location }}</dd>
                    </div>
                    @if($jiri->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('jiri.fields.description') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $jiri->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('jiri.stats.evaluated') }}</dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ $jiri->evaluated->count() }}
                    </dd>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('jiri.stats.evaluators') }}</dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ $jiri->evaluators->count() }}
                    </dd>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('jiri.stats.projects') }}</dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ $jiri->projects->count() }}
                    </dd>
                </div>
            </div>
        </div>

        <!-- Évalués -->
        <x-contact-table
            :title="__('jiri.count.evaluated', ['count' => $jiri->evaluated->count()])"
            :contacts="$jiri->evaluated"
            :empty-message="__('jiri.empty.evaluated')"
        />

        <!-- Évaluateurs -->
        <x-contact-table
            :title="__('jiri.count.evaluators', ['count' => $jiri->evaluators->count()])"
            :contacts="$jiri->evaluators"
            :empty-message="__('jiri.empty.evaluators')"
        />

        <!-- Projets -->
        <x-project-table
            :title="__('jiri.count.projects', ['count' => $jiri->projects->count()])"
            :projects="$jiri->projects"
            :empty-message="__('jiri.empty.projects')"
        />

    </div>
</x-layout>
