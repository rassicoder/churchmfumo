@extends('layouts.church-admin')
@section('active', 'settings')

@section('title', 'Church Settings')
@section('header_kicker', 'Settings')
@section('header_title', 'Church Preferences')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="text-sm text-slate-500">Account</div>
        <div class="text-lg font-semibold mb-4">Profile & Preferences</div>
        <div class="text-sm text-slate-600">Settings are managed by Super Admin. Contact support to update church profile.</div>
    </div>
@endsection

@push('scripts')
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
@endpush
