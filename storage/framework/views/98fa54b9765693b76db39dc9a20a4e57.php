<?php $__env->startSection('title', 'Leaders'); ?>
<?php $__env->startSection('page-title', 'Leaders'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $levels = config('leader.levels', ['association', 'church']);
    $statuses = config('leader.statuses', ['active', 'inactive']);
?>
<div class="section-gap">
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['title' => 'Leaders','headers' => ['Name', 'Email', 'Church', 'Actions'],'searchPlaceholder' => 'Search leaders','tableId' => 'leaders-table']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Leaders','headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Name', 'Email', 'Church', 'Actions']),'search-placeholder' => 'Search leaders','table-id' => 'leaders-table']); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#leaderModal" id="leaderCreateBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Leader
            </button>
         <?php $__env->endSlot(); ?>
        <tr><td colspan="4" class="text-muted">Loading...</td></tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
</div>

<div class="modal fade" id="leaderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="leaderForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaderModalTitle">Create Leader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="leaderId">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="leaderName" required>
                        <div class="invalid-feedback" id="leaderNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" class="form-control" id="leaderPosition" required>
                        <div class="invalid-feedback" id="leaderPositionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="leaderEmail" required>
                        <div class="invalid-feedback" id="leaderEmailError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="leaderPhone">
                        <div class="invalid-feedback" id="leaderPhoneError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Church</label>
                        <select class="form-select" id="leaderChurch" required></select>
                        <div class="invalid-feedback" id="leaderChurchError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select class="form-select" id="leaderLevel" required>
                            <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($level); ?>"><?php echo e(ucfirst($level)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="invalid-feedback" id="leaderLevelError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="leaderStatus" required>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>"><?php echo e(ucfirst($status)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="invalid-feedback" id="leaderStatusError"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Term Start</label>
                            <input type="date" class="form-control" id="leaderTermStart">
                            <div class="invalid-feedback" id="leaderTermStartError"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Term End</label>
                            <input type="date" class="form-control" id="leaderTermEnd">
                            <div class="invalid-feedback" id="leaderTermEndError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="leaderSubmitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="leaderSubmitSpinner"></span>
                        Save Leader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="/js/leaders.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/leaders/index.blade.php ENDPATH**/ ?>