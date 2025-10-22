<x-layout title="Contacts - Jiri">

    <x-slot:header>
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Contacts</h1>
                <x-form.button href="{{ route('contacts.create') }}">
                    {{__('jiri.actions.new_contact')}}
                </x-form.button>
            </div>
        </div>
    </x-slot:header>

    <div class="bg-white shadow rounded-lg overflow-hidden max-w-7xl mx-auto">
        <!-- En-tête du tableau -->

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <x-contact-table
                :title="__('jiri.sections.contact_list')"
                :contacts="$contacts"
                :empty-message="__('jiri.empty.evaluated')"
            />
        </div>

        <!-- Pagination -->
        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</x-layout>
