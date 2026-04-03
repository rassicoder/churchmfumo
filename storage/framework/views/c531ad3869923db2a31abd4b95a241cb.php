<?php $__env->startSection('title', 'Departments'); ?>
<?php $__env->startSection('page-title', 'Departments'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-gap">
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['title' => 'Departments','headers' => ['Department', 'Church', 'Leader', 'Status', 'Actions'],'searchPlaceholder' => 'Search departments','tableId' => 'departments-table']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Departments','headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Department', 'Church', 'Leader', 'Status', 'Actions']),'search-placeholder' => 'Search departments','table-id' => 'departments-table']); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.departments.create')); ?>"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
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
    const departmentsTable = AdminApp.initTable({
        tableId: 'departments-table',
        endpoint: '/api/v1/departments',
        mapRow: function (row) {
            const church = row.church ? row.church.name : '-';
            const leader = row.leader ? row.leader.full_name : '-';
            return '<tr>'
                + '<td>' + (row.name || '-') + '</td>'
                + '<td>' + church + '</td>'
                + '<td>' + leader + '</td>'
                + '<td>' + AdminApp.statusBadge(row.status || 'Active') + '</td>'
                + '<td>'
                + '<div class="dropdown">'
                + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                + '<ul class="dropdown-menu dropdown-menu-end">'
                + '<li><a class="dropdown-item" href="/admin/departments/' + row.id + '/edit">Edit</a></li>'
                + '<li><button class="dropdown-item text-danger" type="button" onclick="deleteDepartment(\'' + row.id + '\')">Delete</button></li>'
                + '</ul>'
                + '</div>'
                + '</td>'
                + '</tr>';
        }
    });

    async function deleteDepartment(id) {
        if (!confirm('Delete this department?')) return;
        await AdminApp.apiDelete('/api/v1/departments/' + id);
        departmentsTable.reload();
    }
    window.deleteDepartment = deleteDepartment;
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/departments/index.blade.php ENDPATH**/ ?>