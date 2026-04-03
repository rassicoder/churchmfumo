<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'uuid', 'exists:projects,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'approved_by' => ['nullable', 'uuid', 'exists:users,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'string', Rule::in(config('finance.expense_statuses', []))],
        ];
    }
}
