<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['required', 'uuid', 'exists:churches,id'],
            'name' => ['required', 'string', 'max:255'],
            'leader_id' => ['nullable', 'uuid', 'exists:leaders,id'],
        ];
    }
}
