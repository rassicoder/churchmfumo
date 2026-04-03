<?php $__env->startSection('title', 'Projects'); ?>
<?php $__env->startSection('page-title', 'Projects'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-gap">
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['title' => 'Projects','headers' => ['Project', 'Leader', 'Progress', 'Status', 'Actions'],'searchPlaceholder' => 'Search projects','tableId' => 'projects-table']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Projects','headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Project', 'Leader', 'Progress', 'Status', 'Actions']),'search-placeholder' => 'Search projects','table-id' => 'projects-table']); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.projects.create')); ?>"><i class="bi bi-plus-circle me-1"></i> Add Project</a>
         <?php $__env->endSlot(); ?>
        <tr><td colspan="5" class="text-muted">Loading...</td></tr>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const projectsTable = AdminApp.initTable({
        tableId: 'projects-table',
        endpoint: '/api/v1/projects',
        mapRow: function (row) {
            const leader = row.leader ? row.leader.full_name : '-';
            return '<tr>'
                + '<td>' + (row.name || '-') + '</td>'
                + '<td>' + leader + '</td>'
                + '<td>' + (row.progress != null ? row.progress + '%' : '-') + '</td>'
                + '<td>' + AdminApp.statusBadge(row.status) + '</td>'
                + '<td>'
                + '<div class="dropdown">'
                + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                + '<ul class="dropdown-menu dropdown-menu-end">'
                + '<li><a class="dropdown-item" href="/admin/projects/' + row.id + '/edit">Edit</a></li>'
                + '<li><button class="dropdown-item text-danger" type="button" onclick="deleteProject(\'' + row.id + '\')">Delete</button></li>'
                + '</ul>'
                + '</div>'
                + '</td>'
                + '</tr>';
        }
    });

    async function deleteProject(id) {
        if (!confirm('Delete this project?')) return;
        await AdminApp.apiDelete('/api/v1/projects/' + id);
        projectsTable.reload();
    }
    window.deleteProject = deleteProject;
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/projects/index.blade.php ENDPATH**/ ?>