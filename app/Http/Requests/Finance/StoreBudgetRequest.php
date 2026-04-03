<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['required', 'uuid', 'exists:churches,id'],
            'department_id' => ['nullable', 'uuid', 'exists:departments,id'],
            'allocated_amount' => ['required', 'numeric', 'min:0'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'approved_by' => ['nullable', 'uuid', 'exists:users,id'],
            'status' => ['required', 'string', Rule::in(config('finance.budget_statuses', []))],
        ];
    }
}
