<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'headers' => [],
    'title' => null,
    'actions' => null,
    'searchPlaceholder' => 'Search...',
    'tableId' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'headers' => [],
    'title' => null,
    'actions' => null,
    'searchPlaceholder' => 'Search...',
    'tableId' => null,
]); ?>
<?php foreach (array_filter(([
    'headers' => [],
    'title' => null,
    'actions' => null,
    'searchPlaceholder' => 'Search...',
    'tableId' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="card p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <?php if($title): ?>
            <h6 class="mb-0 heading"><?php echo e($title); ?></h6>
        <?php endif; ?>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group" style="max-width: 220px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input
                    type="text"
                    class="form-control border-start-0"
                    placeholder="<?php echo e($searchPlaceholder); ?>"
                    <?php if($tableId): ?> data-table-search="<?php echo e($tableId); ?>" <?php endif; ?>
                >
            </div>
            <?php echo e($actions); ?>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" <?php if($tableId): ?> id="<?php echo e($tableId); ?>" <?php endif; ?>>
            <thead>
            <tr>
                <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($header); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            </thead>
            <tbody>
            <?php echo e($slot); ?>

            </tbody>
        </table>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
        <div class="small text-muted" <?php if($tableId): ?> data-table-summary="<?php echo e($tableId); ?>" <?php endif; ?>>Showing 0–0 of 0 results</div>
        <nav class="d-flex align-items-center gap-2 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm" type="button" <?php if($tableId): ?> data-table-prev="<?php echo e($tableId); ?>" <?php endif; ?>>Previous</button>
            <div class="d-flex align-items-center gap-2" <?php if($tableId): ?> data-table-pages="<?php echo e($tableId); ?>" <?php endif; ?>></div>
            <button class="btn btn-outline-secondary btn-sm" type="button" <?php if($tableId): ?> data-table-next="<?php echo e($tableId); ?>" <?php endif; ?>>Next</button>
        </nav>
    </div>
</div>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/components/table.blade.php ENDPATH**/ ?>