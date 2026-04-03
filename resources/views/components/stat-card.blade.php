@props([
    'title' => '',
    'value' => '',
    'icon' => 'bi-circle',
    'trend' => '',
    'valueId' => null,
])

<div class="card p-4 h-100">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="text-muted text-uppercase small">{{ $title }}</div>
            <div class="display-6 fw-bold mb-1">
                @if($valueId)
                    <span id="{{ $valueId }}" class="stat-value">{{ $value }}</span>
                @else
                    {{ $value }}
                @endif
            </div>
            <div class="small text-muted">{{ $trend }}</div>
        </div>
        <div class="icon-pill">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>
</div>
