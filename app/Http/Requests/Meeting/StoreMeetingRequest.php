<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['required', 'uuid', 'exists:churches,id'],
            'meeting_type' => ['required', 'string', Rule::in(config('meeting.meeting_types', []))],
            'meeting_date' => ['required', 'date'],
            'agenda' => ['nullable', 'string'],
            'minutes' => ['nullable', 'string'],
            'created_by' => ['required', 'uuid', 'exists:users,id'],
        ];
    }
}
