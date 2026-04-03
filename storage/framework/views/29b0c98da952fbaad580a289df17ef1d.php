<?php $__env->startSection('active', 'leaders'); ?>

<?php $__env->startSection('title', 'Create Leader'); ?>
<?php $__env->startSection('header_kicker', 'Leaders'); ?>
<?php $__env->startSection('header_title', 'Create Leader'); ?>

<?php $__env->startSection('content'); ?>
    <form id="leaderCreateForm" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-slate-500">Full Name</label>
                <input name="full_name" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">Position</label>
                <input name="position" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-slate-500">Email</label>
                <input name="email" type="email" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-slate-500">Phone</label>
                <input name="phone" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-slate-500">Level</label>
                <select name="level" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
                    <?php $__currentLoopData = config('leader.levels', ['association', 'church']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($level); ?>"><?php echo e(ucfirst($level)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-500">Status</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
                    <?php $__currentLoopData = config('leader.statuses', ['active', 'inactive']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>"><?php echo e(ucfirst($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-500">Term Start</label>
                <input type="date" name="term_start" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-slate-500">Term End</label>
                <input type="date" name="term_end" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2">
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button class="rounded-xl bg-blue-600 text-white px-4 py-2 text-sm" type="submit">Save Leader</button>
            <a href="/admin/church/leaders" class="text-sm text-slate-500">Cancel</a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="/js/church-leaders-create.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/leaders-create.blade.php ENDPATH**/ ?>