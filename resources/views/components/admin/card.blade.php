@props([
    'title' => '',
    'value' => '',
    'icon' => 'bi-circle',
    'subtitle' => null,
])

<div class="card p-4 h-100">
    <div class="card-stat">
        <div>
            <div class="text-muted text-uppercase small">{{ $title }}</div>
            <div class="metric">{{ $value }}</div>
            @if($subtitle)
                <div class="subtitle">{{ $subtitle }}</div>
            @endif
        </div>
        <div class="stat-icon">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>
</div>
