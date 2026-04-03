<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['sometimes', 'required', 'uuid', 'exists:churches,id'],
            'department_id' => ['nullable', 'uuid', 'exists:departments,id'],
            'allocated_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'year' => ['sometimes', 'required', 'integer', 'min:2000', 'max:2100'],
            'approved_by' => ['nullable', 'uuid', 'exists:users,id'],
            'status' => ['sometimes', 'required', 'string', Rule::in(config('finance.budget_statuses', []))],
        ];
    }
}
