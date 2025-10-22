<x-layout title="Projets - Modifier {{ $project->title }}">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('projects.show', $project) }}">
                        ← Retour au projet
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Modifier le projet</h1>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

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
                            :value="old('title', $project->title)"
                            required
                        />

                        <x-form.textarea
                            name="description"
                            label="Description"
                            hint="Optionnel"
                        >{{ old('description', $project->description) }}</x-form.textarea>
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
                                    @php
                                        $isAssigned = $project->jiris->contains($jiri->id);
                                    @endphp
                                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox"
                                               name="jiris[]"
                                               value="{{ $jiri->id }}"
                                               {{ $isAssigned || in_array($jiri->id, old('jiris', [])) ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <div class="ml-3 flex-1">
                                            <span class="text-sm font-medium text-gray-900 block">{{ $jiri->name }}</span>
                                            <span class="text-sm text-gray-500 block mt-1">
                                                {{ $jiri->starts_at->format('d/m/Y à H:i') }} - {{ $jiri->location }}
                                            </span>
                                            @if($jiri->description)
                                                <span class="text-sm text-gray-500 block mt-1">{{ Str::limit($jiri->description, 100) }}</span>
                                            @endif
                                            @if($isAssigned)
                                                @php
                                                    $assignment = $project->assignments->firstWhere('jiri_id', $jiri->id);
                                                    $implementationsCount = $assignment ? $assignment->implementations->count() : 0;
                                                @endphp
                                                @if($implementationsCount > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 mt-2">
                                                        ⚠️ {{ $implementationsCount }} étudiant(s) ont déjà implémenté ce projet pour ce jury
                                                    </span>
                                                @endif
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
                        @if($project->jiris->count() > 0)
                            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" >
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700">
                                            <strong>Attention :</strong> Décocher un jury supprimera les implémentations associées à ce projet pour ce jury.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 flex justify-end gap-3">
                        <x-form.button variant="ghost" href="{{ route('projects.show', $project) }}">
                            Annuler
                        </x-form.button>
                        <x-form.button type="submit">
                            Mettre à jour le projet
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
