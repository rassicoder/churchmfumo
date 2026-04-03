<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'range' => ['nullable', 'string', 'in:7d,30d,year'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
        ];
    }
}
