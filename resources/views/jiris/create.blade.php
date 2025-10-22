<x-layout title="Jiri - Créer un jury">

    <x-slot:header>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('jiris.index') }}">
                        ← Retour à la liste
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Créer un jury</h1>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('jiris.store') }}" method="POST" id="jiri-form">
            @csrf

            <div class="space-y-6">

                <!-- Informations générales -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
                    </div>

                    <div class="px-6 py-4 space-y-6">
                        <x-form.input
                            name="name"
                            label="Nom du jury"
                            :value="old('name')"
                            required
                        />

                        <x-form.input
                            type="datetime-local"
                            name="starts_at"
                            label="Date et heure"
                            :value="old('starts_at')"
                            required
                        />

                        <x-form.input
                            name="location"
                            label="Lieu"
                            :value="old('location')"
                            required
                        />

                        <x-form.textarea
                            name="description"
                            label="Description"
                            hint="Optionnel"
                        >{{ old('description') }}</x-form.textarea>
                    </div>
                </section>

                <!-- Contacts -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Participants ({{ $contacts->count() }} disponible(s))
                        </h2>
                    </div>

                    <div class="px-6 py-4">
                        @if($contacts->count() > 0)
                            <div class="space-y-2">
                                @foreach($contacts as $contact)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <div class="flex items-center flex-1">
                                            <input type="checkbox"
                                                   name="contact_ids[]"
                                                   value="{{ $contact->id }}"
                                                   id="contact-{{ $contact->id }}"
                                                   data-contact-checkbox="{{ $contact->id }}"
                                                   {{ in_array($contact->id, old('contact_ids', [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="contact-{{ $contact->id }}" class="ml-3 cursor-pointer">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                                </span>
                                                <span class="text-sm text-gray-500 ml-2">
                                                    ({{ $contact->email }})
                                                </span>
                                            </label>
                                        </div>
                                        <div class="ml-4">
                                            <label for="role-{{ $contact->id }}" class="sr-only">
                                                Rôle pour {{ $contact->first_name }} {{ $contact->last_name }}
                                            </label>
                                            <select name="contact_roles[{{ $contact->id }}]"
                                                    id="role-{{ $contact->id }}"
                                                    data-contact-role="{{ $contact->id }}"
                                                    disabled
                                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm disabled:bg-gray-100 disabled:text-gray-500">
                                                <option value="">Sélectionner un rôle</option>
                                                <option value="evaluated" {{ old("contact_roles.{$contact->id}") === 'evaluated' ? 'selected' : '' }}>
                                                    Évalué
                                                </option>
                                                <option value="evaluator" {{ old("contact_roles.{$contact->id}") === 'evaluator' ? 'selected' : '' }}>
                                                    Évaluateur
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">Aucun contact disponible.</p>
                                <x-form.button variant="ghost" size="sm" href="{{ route('contacts.create') }}" class="mt-2">
                                    Créer un contact
                                </x-form.button>
                            </div>
                        @endif
                        <p class="mt-4 text-sm text-gray-500">
                            Cochez les contacts à ajouter et sélectionnez leur rôle
                        </p>
                    </div>
                </section>

                <!-- Projets -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Projets ({{ $projects->count() }} disponible(s))
                        </h2>
                    </div>

                    <div class="px-6 py-4">
                        @if($projects->count())
                            <div class="space-y-2">
                                @foreach($projects as $project)
                                    <div class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox"
                                               name="projects[]"
                                               value="{{ $project->id }}"
                                               id="project-{{ $project->id }}"
                                               {{ in_array($project->id, old('projects', [])) ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="project-{{ $project->id }}" class="ml-3 cursor-pointer flex-1">
                                            <span class="text-sm font-medium text-gray-900 block">{{ $project->title }}</span>
                                            @if($project->description)
                                                <span class="text-sm text-gray-500 block mt-1">{{ $project->description }}</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">Aucun projet disponible.</p>
                                <x-form.button variant="ghost" size="sm" href="{{ route('projects.create') }}" class="mt-2">
                                    Créer un projet
                                </x-form.button>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 flex justify-end gap-3">
                        <x-form.button variant="ghost" href="{{ route('jiris.index') }}">
                            Annuler
                        </x-form.button>
                        <x-form.button type="submit">
                            Créer le jury
                        </x-form.button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <x-slot:footer>
    </x-slot:footer>

</x-layout>
