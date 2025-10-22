@props(['contact'])

<tr class="hover:bg-gray-50"
    onclick="window.location='{{ route('contacts.show', $contact) }}'">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            @if($contact->avatar)
                <img src="{{$contact->avatar_url }}"
                     alt="Avatar de {{ $contact->first_name }}"
                     class="h-7 w-7 rounded-full object-cover flex-shrink-0">
            @else
                <div class="h-7 w-7 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                    {{ substr($contact->last_name, 0, 1) }}{{ substr($contact->first_name, 0, 1) }}
                </div>
            @endif
        <div class="text-sm font-medium text-gray-900 ml-4">
            {{ $contact->first_name }} {{ $contact->last_name }}
        </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-500">
            {{ $contact->email }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
        <button class="text-red-600 hover:text-red-900">
            {{ __('jiri.actions.remove') }}
        </button>
    </td>
</tr>
