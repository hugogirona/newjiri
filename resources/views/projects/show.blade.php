<x-layout title="Projet - {{ $project->title }}">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('projects.index') }}">
                        ← Retour à la liste
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $project->title }}
                    </h1>
                </div>
                <div class="flex gap-3">
                    <x-form.button variant="secondary" href="{{ route('projects.edit', $project) }}">
                        Modifier
                    </x-form.button>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                        @csrf
                        @method('DELETE')
                        <x-form.button variant="danger" type="submit">
                            Supprimer
                        </x-form.button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-6">

        <!-- Informations générales -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Titre</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $project->created_at->format('d/m/Y à H:i') }}
                        </dd>
                    </div>
                    @if($project->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dt class="text-sm font-medium text-gray-500 truncate">Jurys assignés</dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ $project->jiris->count() }}
                    </dd>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dt class="text-sm font-medium text-gray-500 truncate">Implémentations</dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ $implementationsCount }}
                    </dd>
                </div>
            </div>
        </div>

        <!-- Jurys où ce projet est assigné -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">
                    Jurys ({{ $project->jiris->count() }})
                </h2>
            </div>
            <div class="overflow-x-auto">
                @if($project->jiris->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom du jury
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lieu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Implémentations
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($project->jiris as $jiri)
                            @php
                                $assignment = $project->assignments->firstWhere('jiri_id', $jiri->id);
                                $implementationsForJiri = $assignment ? $assignment->implementations->count() : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $jiri->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $jiri->starts_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $jiri->starts_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jiri->location }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $implementationsForJiri }} étudiant(s)
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('jiris.show', $jiri) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Voir le jury
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400"  stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Ce projet n'est assigné à aucun jury pour le moment</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Étudiants ayant implémenté ce projet -->
        @if($implementationsCount > 0)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Étudiants ayant réalisé ce projet ({{ $implementationsCount }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Étudiant
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jury
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($implementations as $implementation)
                            @php
                                $contact = $implementation->contact;
                                $jiri = $implementation->assignment->jiri;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($contact->avatar)
                                            <img src="{{ asset('storage/' . $contact->avatar) }}"
                                                 alt="Avatar"
                                                 class="h-8 w-8 rounded-full object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ substr($contact->first_name, 0, 1) }}{{ substr($contact->last_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $contact->first_name }} {{ $contact->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $contact->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jiri->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $jiri->starts_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('contacts.show', $contact) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Voir le contact
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <x-slot:footer>
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
            <p>&copy; 2025 HEPL</p>
        </div>
    </x-slot:footer>

</x-layout>
