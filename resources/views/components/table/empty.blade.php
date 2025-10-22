@props(['colspan' => 3, 'message'])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-8 text-center text-gray-500 text-sm">
        {{ $message }}
    </td>
</tr>
