<x-layout title="Projets - Jiri">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Projets</h1>
                <x-form.button href="{{ route('projects.create') }}">
                    Nouveau projet
                </x-form.button>
            </div>
        </div>
    </x-slot:header>

    <div class="bg-white shadow rounded-lg overflow-hidden max-w-7xl mx-auto">
        <!-- En-tête du tableau -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Liste des projets
                </h2>
                <span class="text-sm text-gray-500">
                    {{ $projects->total() }} projet(s)
                </span>
            </div>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Titre
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jurys
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 cursor-pointer transition"
                        onclick="window.location='{{ route('projects.show', $project) }}'">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $project->title }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-md truncate">
                                {{ $project->description ?? '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $project->jiris_count }} jury(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('projects.show', $project) }}"
                               class="text-indigo-600 hover:text-indigo-900"
                               onclick="event.stopPropagation()">
                                Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                <p class="mt-2 text-sm">Aucun projet pour le moment</p>
                                <x-form.button href="{{ route('projects.create') }}" variant="ghost" size="sm" class="mt-4">
                                    Créer le premier projet
                                </x-form.button>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    <x-slot:footer>
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
            <p>&copy; 2025 HEPL</p>
        </div>
    </x-slot:footer>

</x-layout>
