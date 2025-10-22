<x-layout title="Contacts - Modifier {{ $contact->first_name }} {{ $contact->last_name }}">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('contacts.show', $contact) }}">
                        ← Retour au contact
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Modifier le contact</h1>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data">
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
                            name="first_name"
                            label="Prénom"
                            :value="old('first_name', $contact->first_name)"
                            required
                        />

                        <x-form.input
                            name="last_name"
                            label="Nom"
                            :value="old('last_name', $contact->last_name)"
                            required
                        />

                        <x-form.input
                            type="email"
                            name="email"
                            label="Email"
                            :value="old('email', $contact->email)"
                            required
                        />

                        <!-- Avatar -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">
                                Avatar
                            </label>
                            <div class="mt-1 flex items-center gap-4">
                                <!-- Prévisualisation -->
                                <div id="avatar-preview" class="{{ $contact->avatar ? '' : 'hidden' }}">
                                    <img src="{{ $contact->avatar ? asset('storage/' . $contact->avatar) : '' }}"
                                         alt="Prévisualisation"
                                         class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                                </div>
                                <div id="avatar-placeholder" class="{{ $contact->avatar ? 'hidden' : '' }} h-20 w-20 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                                    {{ substr($contact->first_name, 0, 1) }}{{ substr($contact->last_name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <input
                                        type="file"
                                        name="avatar"
                                        id="avatar"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-lg file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100
                                            cursor-pointer"
                                    >
                                    @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                    @if($contact->avatar)
                                        <label class="inline-flex items-center mt-2">
                                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm text-red-600">Supprimer l'avatar actuel</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Participations aux jurys -->
                <section class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Participations aux jurys ({{ $jiris->count() }} disponible(s))
                        </h2>
                    </div>

                    <div class="px-6 py-4">
                        @if($jiris->count() > 0)
                            <div class="space-y-3">
                                @foreach($jiris as $jiri)
                                    @php
                                        $attendance = $contact->jiris->firstWhere('id', $jiri->id);
                                        $isParticipating = $attendance !== null;
                                        $currentRole = $isParticipating ? $attendance->pivot->role : '';

                                        // Récupérer les projets assignés à ce contact pour ce jury
                                        $assignedProjectIds = [];
                                        if ($isParticipating && $currentRole === 'evaluated') {
                                            $assignedProjectIds = $contact->implementations()
                                                ->whereHas('assignment', function($q) use ($jiri) {
                                                    $q->where('jiri_id', $jiri->id);
                                                })
                                                ->with('assignment.project')
                                                ->get()
                                                ->pluck('assignment.project.id')
                                                ->toArray();
                                        }
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start gap-4">
                                            <!-- Checkbox pour sélectionner le jury -->
                                            <div class="flex items-center h-10">
                                                <input type="checkbox"
                                                       name="jiri_ids[]"
                                                       value="{{ $jiri->id }}"
                                                       id="jiri-{{ $jiri->id }}"
                                                       data-jiri-checkbox="{{ $jiri->id }}"
                                                       {{ $isParticipating || in_array($jiri->id, old('jiri_ids', [])) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            </div>

                                            <!-- Infos du jury -->
                                            <div class="flex-1">
                                                <label for="jiri-{{ $jiri->id }}" class="cursor-pointer">
                                                    <div class="font-medium text-gray-900">{{ $jiri->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $jiri->starts_at->format('d/m/Y à H:i') }} - {{ $jiri->location }}
                                                    </div>
                                                </label>

                                                <!-- Sélection du rôle -->
                                                <div class="mt-3">
                                                    <label for="jiri-role-{{ $jiri->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Rôle dans ce jury
                                                    </label>
                                                    <select name="jiri_roles[{{ $jiri->id }}]"
                                                            id="jiri-role-{{ $jiri->id }}"
                                                            data-jiri-role="{{ $jiri->id }}"
                                                            {{ !$isParticipating ? 'disabled' : '' }}
                                                            class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm disabled:bg-gray-100 disabled:text-gray-500">
                                                        <option value="">Sélectionner un rôle</option>
                                                        <option value="evaluated" {{ old("jiri_roles.{$jiri->id}", $currentRole) === 'evaluated' ? 'selected' : '' }}>
                                                            Évalué
                                                        </option>
                                                        <option value="evaluator" {{ old("jiri_roles.{$jiri->id}", $currentRole) === 'evaluator' ? 'selected' : '' }}>
                                                            Évaluateur
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Sélection des projets (uniquement si évalué) -->
                                                <div class="mt-3 {{ $currentRole === 'evaluated' ? '' : 'hidden' }}"
                                                     id="projects-container-{{ $jiri->id }}"
                                                     data-projects-container="{{ $jiri->id }}">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        Projets à réaliser
                                                    </label>
                                                    @if($jiri->projects->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach($jiri->projects as $project)
                                                                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                                    <input type="checkbox"
                                                                           name="jiri_projects[{{ $jiri->id }}][]"
                                                                           value="{{ $project->id }}"
                                                                           {{ in_array($project->id, old("jiri_projects.{$jiri->id}", $assignedProjectIds)) ? 'checked' : '' }}
                                                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                                    <span class="ml-2 text-sm text-gray-900">{{ $project->title }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-sm text-gray-500">Aucun projet assigné à ce jury</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                            Cochez les jurys auxquels ce contact participera et définissez son rôle
                        </p>
                    </div>
                </section>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 flex justify-end gap-3">
                        <x-form.button variant="ghost" href="{{ route('contacts.show', $contact) }}">
                            Annuler
                        </x-form.button>
                        <x-form.button type="submit">
                            Mettre à jour le contact
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la prévisualisation de l'avatar
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarPlaceholder = document.getElementById('avatar-placeholder');
            const previewImage = avatarPreview.querySelector('img');

            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        avatarPreview.classList.remove('hidden');
                        avatarPlaceholder.classList.add('hidden');
                    };

                    reader.readAsDataURL(file);
                } else {
                    if (!previewImage.src.includes('storage')) {
                        avatarPreview.classList.add('hidden');
                        avatarPlaceholder.classList.remove('hidden');
                    }
                }
            });

            // Gestion des jurys et rôles
            document.querySelectorAll('[data-jiri-checkbox]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const jiriId = this.dataset.jiriCheckbox;
                    const roleSelect = document.querySelector(`[data-jiri-role="${jiriId}"]`);

                    if (this.checked) {
                        roleSelect.disabled = false;
                        roleSelect.required = true;
                        handleRoleChange(jiriId, roleSelect.value);
                    } else {
                        roleSelect.disabled = true;
                        roleSelect.required = false;
                        roleSelect.value = '';
                        hideProjects(jiriId);
                    }
                });

                // Initialiser l'état si déjà coché
                if (checkbox.checked) {
                    const jiriId = checkbox.dataset.jiriCheckbox;
                    const roleSelect = document.querySelector(`[data-jiri-role="${jiriId}"]`);
                    roleSelect.disabled = false;
                    roleSelect.required = true;
                }
            });

            // Gestion du changement de rôle
            document.querySelectorAll('[data-jiri-role]').forEach(select => {
                select.addEventListener('change', function() {
                    const jiriId = this.dataset.jiriRole;
                    handleRoleChange(jiriId, this.value);
                });
            });

            function handleRoleChange(jiriId, role) {
                const projectsContainer = document.querySelector(`[data-projects-container="${jiriId}"]`);

                if (role === 'evaluated') {
                    projectsContainer.classList.remove('hidden');
                } else {
                    projectsContainer.classList.add('hidden');
                }
            }

            function hideProjects(jiriId) {
                const projectsContainer = document.querySelector(`[data-projects-container="${jiriId}"]`);
                projectsContainer.classList.add('hidden');
            }
        });
    </script>

</x-layout>
