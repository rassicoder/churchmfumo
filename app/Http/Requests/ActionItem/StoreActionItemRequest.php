<?php

namespace App\Http\Requests\ActionItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'responsible_leader_id' => ['required', 'uuid', 'exists:leaders,id'],
            'deadline' => ['required', 'date'],
            'status' => ['nullable', 'string', Rule::in(config('meeting.action_item_statuses', []))],
        ];
    }
}
