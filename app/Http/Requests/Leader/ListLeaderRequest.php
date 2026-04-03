<?php

namespace App\Http\Requests\Leader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListLeaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
            'level' => ['nullable', 'string', Rule::in(config('leader.levels', []))],
            'status' => ['nullable', 'string', Rule::in(config('leader.statuses', []))],
            'term_state' => ['nullable', 'string', Rule::in(['active', 'expiring', 'expired'])],
            'search' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
