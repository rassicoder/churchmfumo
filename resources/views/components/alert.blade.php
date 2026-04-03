@props([
    'icon' => 'bi-exclamation-triangle',
    'title' => '',
    'message' => '',
    'badge' => null,
    'badgeClass' => 'text-bg-warning',
    'time' => '',
])

<div class="d-flex align-items-start gap-3 py-2">
    <div class="icon-pill">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="fw-semibold">{{ $title }}</div>
                <div class="text-muted small">{{ $message }}</div>
            </div>
            @if($badge)
                <span class="badge {{ $badgeClass }} badge-status">{{ $badge }}</span>
            @endif
        </div>
        <div class="text-muted small mt-1">{{ $time }}</div>
    </div>
</div>
