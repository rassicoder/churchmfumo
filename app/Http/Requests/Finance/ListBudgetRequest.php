<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
            'department_id' => ['nullable', 'uuid', 'exists:departments,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'status' => ['nullable', 'string', Rule::in(config('finance.budget_statuses', []))],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
