<?php

namespace App\Http\Requests\ActionItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'required', 'string'],
            'responsible_leader_id' => ['sometimes', 'required', 'uuid', 'exists:leaders,id'],
            'deadline' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', 'string', Rule::in(config('meeting.action_item_statuses', []))],
        ];
    }
}
