<?php $__env->startSection('active', 'projects'); ?>

<?php $__env->startSection('title', 'Create Project'); ?>
<?php $__env->startSection('header_kicker', 'Projects'); ?>
<?php $__env->startSection('header_title', 'Create Project'); ?>

<?php $__env->startSection('content'); ?>
    <form id="projectCreateForm" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm text-slate-500">Project Name</label>
                <input name="name" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">Start Date</label>
                <input type="date" name="start_date" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">End Date</label>
                <input type="date" name="end_date" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">Budget</label>
                <input type="number" name="budget" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" min="0">
            </div>
            <div>
                <label class="text-sm text-slate-500">Progress (%)</label>
                <input type="number" name="progress" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" min="0" max="100" value="0" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">Status</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
                    <?php $__currentLoopData = config('project.statuses', ['planned', 'active', 'completed']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>"><?php echo e(ucfirst($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-slate-500">Description</label>
                <textarea name="description" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" rows="3"></textarea>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button class="rounded-xl bg-blue-600 text-white px-4 py-2 text-sm" type="submit">Save Project</button>
            <a href="/admin/church/projects" class="text-sm text-slate-500">Cancel</a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="/js/church-projects-create.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/projects-create.blade.php ENDPATH**/ ?>