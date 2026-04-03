@extends('layouts.admin')

@section('title', 'Edit Leader')
@section('page-title', 'Edit Leader')

@section('content')
@php
    $levels = config('leader.levels', ['association', 'church']);
    $statuses = config('leader.statuses', ['active', 'inactive']);
@endphp
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">Edit Leader</h6>
        <form id="leader-edit-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Church</label>
                    <select class="form-select" name="church_id" id="leader-church"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Level</label>
                    <select class="form-select" name="level">
                        @foreach($levels as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Term Start</label>
                    <input type="date" class="form-control" name="term_start">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Term End</label>
                    <input type="date" class="form-control" name="term_end">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="{{ route('admin.leaders') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const leaderId = "{{ request()->route('id') }}";

    AdminApp.initSelect({
        selectId: 'leader-church',
        endpoint: '/api/v1/churches?per_page=100',
        valueKey: 'id',
        labelKey: 'name',
        placeholder: 'Select church'
    }).then(function () {
        AdminApp.apiGet('/api/v1/leaders/' + leaderId).then(function (data) {
            const leader = data.data;
            const form = document.getElementById('leader-edit-form');
            form.church_id.value = leader.church_id || '';
            form.full_name.value = leader.full_name || '';
            form.position.value = leader.position || '';
            form.level.value = leader.level || '';
            form.term_start.value = leader.term_start || '';
            form.term_end.value = leader.term_end || '';
            form.phone.value = leader.phone || '';
            form.email.value = leader.email || '';
            form.status.value = leader.status || '';
            AdminApp.getCurrentUser().then(function (profile) {
                const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
                if (role === 'Church Admin') {
                    const select = document.getElementById('leader-church');
                    if (select) {
                        select.value = profile.church_id || form.church_id.value || '';
                        select.disabled = true;
                        const wrapper = select.closest('.col-md-6');
                        if (wrapper) wrapper.classList.add('d-none');
                    }
                }
            });
        });
    });

    document.getElementById('leader-edit-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            church_id: form.church_id.value || null,
            full_name: form.full_name.value,
            position: form.position.value,
            level: form.level.value,
            term_start: form.term_start.value || null,
            term_end: form.term_end.value || null,
            phone: form.phone.value,
            email: form.email.value,
            status: form.status.value,
        };
        await AdminApp.apiPut('/api/v1/leaders/' + leaderId, payload);
        window.location.href = '/admin/leaders';
    });
</script>
@endpush
