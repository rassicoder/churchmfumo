<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class FinancialSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ];
    }
}
