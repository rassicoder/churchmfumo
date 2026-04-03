<?php $__env->startSection('active', 'leaders'); ?>

<?php $__env->startSection('title', 'Church Leaders'); ?>
<?php $__env->startSection('header_kicker', 'Leaders'); ?>
<?php $__env->startSection('header_title', 'Leadership Directory'); ?>
<?php $__env->startSection('header_right'); ?>
    <a href="/admin/church/leaders/create" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm shadow">Add Leader</a>
    <button data-logout class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Logout</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm text-slate-500">Church Leaders</div>
                <div class="text-lg font-semibold">Latest Updates</div>
            </div>
            <input id="leaderSearch" type="text" placeholder="Search leaders" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-slate-400">
                    <tr>
                        <th class="py-2">Name</th>
                        <th class="py-2">Position</th>
                        <th class="py-2">Phone</th>
                        <th class="py-2">Email</th>
                    </tr>
                </thead>
                <tbody id="leadersList" class="text-slate-700">
                    <tr><td class="py-3" colspan="4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="leadersEmpty" class="text-sm text-slate-500 mt-3 hidden">No leaders available.</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="/js/church-leaders.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/leaders.blade.php ENDPATH**/ ?>