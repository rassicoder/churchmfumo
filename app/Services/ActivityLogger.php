<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function log(Request $request, string $action, ?string $table, ?string $recordId): void
    {
        ActivityLog::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'table' => $table ?: 'unknown',
            'record_id' => $recordId,
            'ip_address' => $request->ip(),
        ]);
    }
}
