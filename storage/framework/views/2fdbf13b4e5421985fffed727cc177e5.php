<?php $__env->startSection('active', 'meetings'); ?>

<?php $__env->startSection('title', 'Schedule Meeting'); ?>
<?php $__env->startSection('header_kicker', 'Meetings'); ?>
<?php $__env->startSection('header_title', 'Schedule Meeting'); ?>

<?php $__env->startSection('content'); ?>
    <form id="meetingCreateForm" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-slate-500">Meeting Type</label>
                <select name="meeting_type" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
                    <?php $__currentLoopData = config('meeting.meeting_types', ['general', 'leadership']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>"><?php echo e(ucfirst($type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-500">Meeting Date</label>
                <input type="date" name="meeting_date" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-slate-500">Agenda</label>
                <textarea name="agenda" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" rows="3"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-slate-500">Minutes</label>
                <textarea name="minutes" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2" rows="4"></textarea>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button class="rounded-xl bg-blue-600 text-white px-4 py-2 text-sm" type="submit">Save Meeting</button>
            <a href="/admin/church/meetings" class="text-sm text-slate-500">Cancel</a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="/js/church-meetings-create.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/meetings-create.blade.php ENDPATH**/ ?>