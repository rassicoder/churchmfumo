<?php $__env->startSection('title', 'Churches'); ?>
<?php $__env->startSection('page-title', 'Churches'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-gap">
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['title' => 'Churches','headers' => ['Name', 'Location', 'Created Date', 'Actions'],'searchPlaceholder' => 'Search churches','tableId' => 'churches-table']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Churches','headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Name', 'Location', 'Created Date', 'Actions']),'search-placeholder' => 'Search churches','table-id' => 'churches-table']); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#churchModal" id="churchCreateBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Church
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

<div class="modal fade" id="churchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="churchForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="churchModalTitle">Create Church</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="churchId">
                    <div class="mb-3">
                        <div class="fw-semibold text-muted small text-uppercase">Church Information</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="churchName" required>
                        <div class="invalid-feedback" id="churchNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" id="churchLocation" required>
                        <div class="invalid-feedback" id="churchLocationError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="churchStatus" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="churchStatusError"></div>
                    </div>
                    <div class="mb-3" id="churchAdminSection">
                        <div class="fw-semibold text-muted small text-uppercase">Admin Information</div>
                    </div>
                    <div class="mb-3" id="churchAdminFields">
                        <label class="form-label">Admin Name</label>
                        <input type="text" class="form-control" id="churchAdminName" required>
                        <div class="invalid-feedback" id="churchAdminNameError"></div>
                    </div>
                    <div class="mb-3" id="churchAdminFieldsEmail">
                        <label class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="churchAdminEmail" required>
                        <div class="invalid-feedback" id="churchAdminEmailError"></div>
                    </div>
                    <div class="mb-3" id="churchAdminFieldsPassword">
                        <label class="form-label">Admin Password</label>
                        <input type="password" class="form-control" id="churchAdminPassword" required>
                        <div class="invalid-feedback" id="churchAdminPasswordError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="churchSubmitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="churchSubmitSpinner"></span>
                        Save Church
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="/js/churches.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/churches/index.blade.php ENDPATH**/ ?>