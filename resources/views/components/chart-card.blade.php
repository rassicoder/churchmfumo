@props([
    'title' => '',
    'subtitle' => null,
    'action' => null,
    'canvasId' => '',
])

<div class="card p-4 h-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            @if($subtitle)
                <div class="text-muted text-uppercase small">{{ $subtitle }}</div>
            @endif
            <h6 class="mb-0 heading">{{ $title }}</h6>
        </div>
        <div>{{ $action }}</div>
    </div>
    <div class="chart-shell">
        <canvas id="{{ $canvasId }}" height="140"></canvas>
        <div class="chart-placeholder" data-chart-placeholder="{{ $canvasId }}">Loading chart...</div>
    </div>
</div>
