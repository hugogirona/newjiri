<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ContactRoles;

class StoreContactRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:contacts,email',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

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
