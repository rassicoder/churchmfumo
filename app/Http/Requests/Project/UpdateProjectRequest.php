<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'leader_id' => ['nullable', 'uuid', 'exists:leaders,id'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'progress' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'status' => ['sometimes', 'required', 'string', Rule::in(config('project.statuses', []))],
        ];
    }
}
