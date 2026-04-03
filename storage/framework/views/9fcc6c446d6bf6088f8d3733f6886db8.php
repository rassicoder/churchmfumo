<?php $__env->startSection('active', 'settings'); ?>

<?php $__env->startSection('title', 'Church Settings'); ?>
<?php $__env->startSection('header_kicker', 'Settings'); ?>
<?php $__env->startSection('header_title', 'Church Preferences'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="text-sm text-slate-500">Account</div>
        <div class="text-lg font-semibold mb-4">Profile & Preferences</div>
        <div class="text-sm text-slate-600">Settings are managed by Super Admin. Contact support to update church profile.</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            if (typeof AdminApp === 'undefined') return;
            const profile = await AdminApp.getCurrentUser();
            const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
            if (String(role || '').toLowerCase() !== 'church admin') {
                if (window.AdminApp && typeof AdminApp.redirectNotAllowed === 'function') {
                    AdminApp.redirectNotAllowed('/admin/dashboard');
                } else {
                    window.location.href = '/admin/dashboard';
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.church-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/church/settings.blade.php ENDPATH**/ ?>