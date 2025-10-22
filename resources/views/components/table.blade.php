<section class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">
            {{ $title }}
        </h2>
        @if($addRoute)
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm font-medium transition">
                {{ __('jiri.actions.add') }}
            </button>
        @endif
    </div>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</section>
