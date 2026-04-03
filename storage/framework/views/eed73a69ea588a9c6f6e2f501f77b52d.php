<?php $__env->startSection('active', 'meetings'); ?>

<?php $__env->startSection('title', 'Church Meetings'); ?>
<?php $__env->startSection('header_kicker', 'Meetings'); ?>
<?php $__env->startSection('header_title', 'Upcoming Meetings'); ?>
<?php $__env->startSection('header_right'); ?>
    <a href="/admin/church/meetings/create" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm shadow">Schedule Meeting</a>
    <button data-logout class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Logout</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm text-slate-500">Meetings</div>
                <div class="text-lg font-semibold">Latest Meetings</div>
            </div>
        </div>
        <div id="meetingsList" class="space-y-3 text-sm text-slate-700">
            <div class="text-slate-500">Loading...</div>
        </div>
        <div id="meetingsEmpty" class="text-sm text-slate-500 mt-3 hidden">No meetings available.</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="/js/church-meetings.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/meetings.blade.php ENDPATH**/ ?>