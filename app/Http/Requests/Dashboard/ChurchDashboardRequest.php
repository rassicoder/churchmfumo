<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ChurchDashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['required', 'uuid', 'exists:churches,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ];
    }
}
