@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="section-gap">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 heading">Theme Mode</h6>
                <p class="text-muted mb-0">Switch between light and dark mode.</p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="themeToggle">
                <label class="form-check-label" for="themeToggle">Dark mode</label>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const toggle = document.getElementById('themeToggle');
        if (!toggle) return;

        const current = localStorage.getItem('admin_theme') || 'light';
        toggle.checked = current === 'dark';

        toggle.addEventListener('change', function () {
            const next = toggle.checked ? 'dark' : 'light';
            localStorage.setItem('admin_theme', next);
            document.documentElement.setAttribute('data-theme', next);
        });
    })();
</script>
@endpush
