<x-table :title="$title" :count="$contacts->count()">
    <table class="min-w-full divide-y divide-gray-200">
        <x-table.head :columns="[
            ['label' => __('jiri.fields.name')],
            ['label' => __('jiri.fields.email')],
            ['label' => __('jiri.fields.actions'), 'align' => 'text-right'],
        ]" />

        <tbody class="bg-white divide-y divide-gray-200">
        @forelse($contacts as $contact)
            <x-table.contact-row :contact="$contact" />
        @empty
            <x-table.empty :message="$emptyMessage" />
        @endforelse
        </tbody>
    </table>
</x-table>
