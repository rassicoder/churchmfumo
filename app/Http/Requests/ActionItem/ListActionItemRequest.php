<?php

namespace App\Http\Requests\ActionItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListActionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meeting_id' => ['nullable', 'uuid', 'exists:meetings,id'],
            'church_id' => ['nullable', 'uuid', 'exists:churches,id'],
            'responsible_leader_id' => ['nullable', 'uuid', 'exists:leaders,id'],
            'status' => ['nullable', 'string', Rule::in(config('meeting.action_item_statuses', []))],
            'overdue_only' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
