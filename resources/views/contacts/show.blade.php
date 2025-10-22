<x-layout title="Contact - {{ $contact->first_name }} {{ $contact->last_name }}">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-form.button variant="ghost" size="sm" href="{{ route('contacts.index') }}">
                        ← Retour à la liste
                    </x-form.button>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $contact->first_name }} {{ $contact->last_name }}
                    </h1>
                </div>
                <div class="flex gap-3">
                    <x-form.button variant="secondary" href="{{ route('contacts.edit', $contact) }}">
                        Modifier
                    </x-form.button>
                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?');">
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

    <div class="space-y-6 max-w-7xl mx-auto">

        <!-- Informations générales -->
        <div class="bg-white shadow rounded-lg overflow-hidden ">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
            </div>
            <div class="px-6 py-6">
                <div class="flex items-start gap-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($contact->avatar)
                            <img src="{{ $contact->avatarUrl }}"
                                 alt="Avatar de {{ $contact->first_name }}"
                                 class="h-24 w-24 rounded-full object-cover border-4 border-gray-100">
                        @else
                            <div class="h-24 w-24 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold border-4 border-gray-100">
                                {{ substr($contact->first_name, 0, 1) }}{{ substr($contact->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Informations -->
                    <div class="flex-1">
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Prénom</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contact->first_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contact->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $contact->email }}" class="text-indigo-600 hover:text-indigo-700">
                                        {{ $contact->email }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $contact->created_at->format('d/m/Y à H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participations aux jurys -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">
                    Participations aux jurys ({{ $contact->jiris->count() }})
                </h2>
            </div>
            <div class="overflow-x-auto">
                @if($contact->jiris->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jury
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rôle
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contact->jiris as $jiri)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $jiri->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $jiri->location }}
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
                                    @php
                                        $role = $jiri->pivot->role;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $role === 'evaluator' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $role === 'evaluator' ? 'Évaluateur' : 'Évalué' }}
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
                        <p class="mt-2 text-sm text-gray-500">Ce contact ne participe à aucun jury pour le moment</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Implémentations de projets (si évalué) -->
        @if($contact->implementations->count() > 0)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Projets réalisés ({{ $contact->implementations->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Projet
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jury
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contact->implementations as $implementation)
                            @php
                                $assignment = $implementation->assignment;
                                $project = $assignment->project;
                                $jiri = $assignment->jiri;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $project->title }}
                                    </div>
                                    @if($project->description)
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($project->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jiri->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $jiri->starts_at->format('d/m/Y') }}
                                    </div>
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
