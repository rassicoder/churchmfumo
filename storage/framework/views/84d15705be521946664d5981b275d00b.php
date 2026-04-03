<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => '',
    'value' => '',
    'icon' => 'bi-circle',
    'trend' => '',
    'valueId' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => '',
    'value' => '',
    'icon' => 'bi-circle',
    'trend' => '',
    'valueId' => null,
]); ?>
<?php foreach (array_filter(([
    'title' => '',
    'value' => '',
    'icon' => 'bi-circle',
    'trend' => '',
    'valueId' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="card p-4 h-100">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="text-muted text-uppercase small"><?php echo e($title); ?></div>
            <div class="display-6 fw-bold mb-1">
                <?php if($valueId): ?>
                    <span id="<?php echo e($valueId); ?>" class="stat-value"><?php echo e($value); ?></span>
                <?php else: ?>
                    <?php echo e($value); ?>

                <?php endif; ?>
            </div>
            <div class="small text-muted"><?php echo e($trend); ?></div>
        </div>
        <div class="icon-pill">
            <i class="bi <?php echo e($icon); ?>"></i>
        </div>
    </div>
</div>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/components/stat-card.blade.php ENDPATH**/ ?>