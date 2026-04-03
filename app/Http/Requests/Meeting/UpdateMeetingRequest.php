<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['sometimes', 'required', 'uuid', 'exists:churches,id'],
            'meeting_type' => ['sometimes', 'required', 'string', Rule::in(config('meeting.meeting_types', []))],
            'meeting_date' => ['sometimes', 'required', 'date'],
            'agenda' => ['nullable', 'string'],
            'minutes' => ['nullable', 'string'],
            'created_by' => ['sometimes', 'required', 'uuid', 'exists:users,id'],
        ];
    }
}
