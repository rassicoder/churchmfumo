<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['sometimes', 'required', 'uuid', 'exists:projects,id'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'approved_by' => ['nullable', 'uuid', 'exists:users,id'],
            'date' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', 'string', Rule::in(config('finance.expense_statuses', []))],
        ];
    }
}
