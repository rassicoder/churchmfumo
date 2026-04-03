<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class ListDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
            'leader_id' => ['nullable', 'uuid', 'exists:leaders,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
