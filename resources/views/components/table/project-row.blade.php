@props(['project'])

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">
            {{ $project->title }}
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm text-gray-500 max-w-xs truncate">
            {{ $project->description ?? __('jiri.empty.no_description') }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
        <button class="text-red-600 hover:text-red-900">
            {{ __('jiri.actions.remove') }}
        </button>
    </td>
</tr>
