<?php

namespace App\Http\Requests;

use App\Enums\ContactRoles;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJiriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_ids' => 'nullable|array',
            'contact_ids.*' => 'exists:contacts,id',
            'contact_roles' => 'nullable|array',
            'contact_roles.*' => ['required_with:contact_ids', Rule::enum(ContactRoles::class)],
            'projects' => 'nullable|array',
            'projects.*' => 'exists:projects,id',
        ];
    }
}
