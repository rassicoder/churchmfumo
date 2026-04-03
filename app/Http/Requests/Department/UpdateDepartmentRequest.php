<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['sometimes', 'required', 'uuid', 'exists:churches,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'leader_id' => ['nullable', 'uuid', 'exists:leaders,id'],
        ];
    }
}
