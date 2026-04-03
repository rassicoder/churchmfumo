<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Overview'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-loading align-items-center gap-2 mb-3" id="dashboardLoading">
    <div class="spinner-border spinner-border-sm" role="status"></div>
    <div>Loading dashboard...</div>
</div>
<div class="alert alert-warning d-none" id="dashboardError"></div>

<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted small">Enterprise snapshot</div>
        <button class="btn btn-outline-secondary btn-sm" id="dashboardRefresh">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3" id="totalChurchesCard">
                <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Churches','value' => '--','valueId' => 'totalChurches','icon' => 'bi-buildings','trend' => 'Live count across associations']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Churches','value' => '--','value-id' => 'totalChurches','icon' => 'bi-buildings','trend' => 'Live count across associations']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Leaders','value' => '--','valueId' => 'totalLeaders','icon' => 'bi-people','trend' => 'Leadership roster']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Leaders','value' => '--','value-id' => 'totalLeaders','icon' => 'bi-people','trend' => 'Leadership roster']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Projects','value' => '--','valueId' => 'totalProjects','icon' => 'bi-kanban','trend' => 'Active initiatives']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Projects','value' => '--','value-id' => 'totalProjects','icon' => 'bi-kanban','trend' => 'Active initiatives']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Meetings','value' => '--','valueId' => 'totalMeetings','icon' => 'bi-calendar-event','trend' => 'Scheduled this year']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Meetings','value' => '--','value-id' => 'totalMeetings','icon' => 'bi-calendar-event','trend' => 'Scheduled this year']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <?php if (isset($component)) { $__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chart-card','data' => ['title' => 'Church Performance Comparison','subtitle' => 'Analytics','canvasId' => 'churchPerformanceChart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('chart-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Church Performance Comparison','subtitle' => 'Analytics','canvas-id' => 'churchPerformanceChart']); ?>
                     <?php $__env->slot('action', null, []); ?> 
                        <button class="btn btn-accent btn-sm">Export</button>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada)): ?>
<?php $attributes = $__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada; ?>
<?php unset($__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada)): ?>
<?php $component = $__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada; ?>
<?php unset($__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada); ?>
<?php endif; ?>
            </div>
            <div class="col-12 col-lg-5">
                <?php if (isset($component)) { $__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chart-card','data' => ['title' => 'Monthly Financial Trend','subtitle' => 'Finance','canvasId' => 'financialTrendChart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('chart-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Monthly Financial Trend','subtitle' => 'Finance','canvas-id' => 'financialTrendChart']); ?>
                     <?php $__env->slot('action', null, []); ?> 
                        <button class="btn btn-primary btn-sm">View</button>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada)): ?>
<?php $attributes = $__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada; ?>
<?php unset($__attributesOriginal1e688d2902fcdda6eea9b1dbdf733ada); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada)): ?>
<?php $component = $__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada; ?>
<?php unset($__componentOriginal1e688d2902fcdda6eea9b1dbdf733ada); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-xl-6">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 heading">Recent Activity</h6>
                        <button class="btn btn-outline-secondary btn-sm">View All</button>
                    </div>
                    <div class="d-flex flex-column gap-3" id="activityFeed">
                        <div class="text-muted small">Loading activity...</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 heading">Alerts & Notifications</h6>
                        <button class="btn btn-outline-secondary btn-sm">Resolve</button>
                    </div>
                    <div class="d-flex flex-column gap-2" id="alertsFeed">
                        <div class="text-muted small">Loading alerts...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-gap">
    <div class="row g-4">
        <div class="col-12">
            <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['title' => 'Leaders Table','headers' => ['Name', 'Position', 'Church', 'Status', 'Actions'],'searchPlaceholder' => 'Search leaders']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Leaders Table','headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Name', 'Position', 'Church', 'Status', 'Actions']),'search-placeholder' => 'Search leaders']); ?>
                 <?php $__env->slot('actions', null, []); ?> 
                    <button class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Leader</button>
                 <?php $__env->endSlot(); ?>
                <tr>
                    <td>Sarah K.</td>
                    <td>Secretary</td>
                    <td>Grace Chapel</td>
                    <td><span class="badge text-bg-success badge-status">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Joseph M.</td>
                    <td>Department Leader</td>
                    <td>St. Mark Church</td>
                    <td><span class="badge text-bg-warning badge-status">Pending</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Grace T.</td>
                    <td>Treasurer</td>
                    <td>Hope Assembly</td>
                    <td><span class="badge text-bg-danger badge-status">Overdue</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="/js/dashboard.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>