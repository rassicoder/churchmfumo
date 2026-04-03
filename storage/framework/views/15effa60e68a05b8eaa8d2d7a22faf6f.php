<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => '',
    'subtitle' => null,
    'action' => null,
    'canvasId' => '',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => '',
    'subtitle' => null,
    'action' => null,
    'canvasId' => '',
]); ?>
<?php foreach (array_filter(([
    'title' => '',
    'subtitle' => null,
    'action' => null,
    'canvasId' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="card p-4 h-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <?php if($subtitle): ?>
                <div class="text-muted text-uppercase small"><?php echo e($subtitle); ?></div>
            <?php endif; ?>
            <h6 class="mb-0 heading"><?php echo e($title); ?></h6>
        </div>
        <div><?php echo e($action); ?></div>
    </div>
    <div class="chart-shell">
        <canvas id="<?php echo e($canvasId); ?>" height="140"></canvas>
        <div class="chart-placeholder" data-chart-placeholder="<?php echo e($canvasId); ?>">Loading chart...</div>
    </div>
</div>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/components/chart-card.blade.php ENDPATH**/ ?>