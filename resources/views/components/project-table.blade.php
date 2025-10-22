<x-table :title="$title" :count="$projects->count()">
    <table class="min-w-full divide-y divide-gray-200">
        <x-table.head :columns="[
            ['label' => __('jiri.fields.name')],
            ['label' => __('jiri.fields.description')],
            ['label' => __('jiri.fields.actions'), 'align' => 'text-right'],
        ]" />

        <tbody class="bg-white divide-y divide-gray-200">
        @forelse($projects as $project)
            <x-table.project-row :project="$project" />
        @empty
            <x-table.empty :message="$emptyMessage" />
        @endforelse
        </tbody>
    </table>
</x-table>
