<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ContactRoles;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts', 'email')->ignore($this->contact->id)
            ],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
            'jiri_ids' => 'nullable|array',
            'jiri_ids.*' => 'exists:jiris,id',
            'jiri_roles' => 'nullable|array',
            'jiri_roles.*' => ['required', Rule::enum(ContactRoles::class)],
            'jiri_projects' => 'nullable|array',
            'jiri_projects.*' => 'array',
            'jiri_projects.*.*' => 'exists:projects,id',
        ];
    }
}
