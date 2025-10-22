<x-layout title="Projets - Créer un projet">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('projects.index') }}">
                        ← Retour à la liste
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Créer un projet</h1>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="space-y-6">

                <!-- Informations générales -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <x-form.input
                            name="title"
                            label="Titre du projet"
                            :value="old('title')"
                            required
                        />

                        <x-form.textarea
                            name="description"
                            label="Description"
                            hint="Optionnel"
                        >{{ old('description') }}</x-form.textarea>
                    </div>
                </section>

                <!-- Assignation aux jurys -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Assignation aux jurys ({{ $jiris->count() }} disponible(s))
                        </h2>
                    </div>

                    <div class="px-6 py-4">
                        @if($jiris->count() > 0)
                            <div class="space-y-2">
                                @foreach($jiris as $jiri)
                                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox"
                                               name="jiris[]"
                                               value="{{ $jiri->id }}"
                                               {{ in_array($jiri->id, old('jiris', [])) ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <div class="ml-3 flex-1">
                                            <span class="text-sm font-medium text-gray-900 block">{{ $jiri->name }}</span>
                                            <span class="text-sm text-gray-500 block mt-1">
                                                {{ $jiri->starts_at->format('d/m/Y à H:i') }} - {{ $jiri->location }}
                                            </span>
                                            @if($jiri->description)
                                                <span class="text-sm text-gray-500 block mt-1">{{ Str::limit($jiri->description, 100) }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">Aucun jury disponible.</p>
                                <x-form.button variant="ghost" size="sm" href="{{ route('jiris.create') }}" class="mt-2">
                                    Créer un jury
                                </x-form.button>
                            </div>
                        @endif
                        <p class="mt-4 text-sm text-gray-500">
                            Cochez les jurys auxquels ce projet sera assigné
                        </p>
                    </div>
                </section>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 flex justify-end gap-3">
                        <x-form.button variant="ghost" href="{{ route('projects.index') }}">
                            Annuler
                        </x-form.button>
                        <x-form.button type="submit">
                            Créer le projet
                        </x-form.button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <x-slot:footer>
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
            <p>&copy; 2025 HEPL</p>
        </div>
    </x-slot:footer>

</x-layout>
