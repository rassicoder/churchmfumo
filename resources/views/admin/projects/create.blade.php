@extends('layouts.admin')

@section('title', 'Create Project')
@section('page-title', 'Create Project')

@section('content')
@php
    $statuses = config('project.statuses', ['planned', 'active', 'completed']);
@endphp
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">New Project</h6>
        <form id="project-create-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Church</label>
                    <select class="form-select" name="church_id" id="project-church"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Leader</label>
                    <select class="form-select" name="leader_id" id="project-leader"></select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Budget</label>
                    <input type="number" step="0.01" class="form-control" name="budget">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Progress (%)</label>
                    <input type="number" min="0" max="100" class="form-control" name="progress">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ route('admin.projects') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AdminApp.initSelect({
        selectId: 'project-church',
        endpoint: '/api/v1/churches?per_page=100',
        valueKey: 'id',
        labelKey: 'name',
        placeholder: 'Select church'
    }).then(function () {
        AdminApp.getCurrentUser().then(function (profile) {
            const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
            if (role === 'Church Admin') {
                const select = document.getElementById('project-church');
                if (select) {
                    select.value = profile.church_id || '';
                    select.disabled = true;
                    const wrapper = select.closest('.col-md-6');
                    if (wrapper) wrapper.classList.add('d-none');
                }
            }
        });
    });
    AdminApp.initSelect({
        selectId: 'project-leader',
        endpoint: '/api/v1/leaders?per_page=100',
        valueKey: 'id',
        labelKey: 'full_name',
        placeholder: 'Select leader'
    });

    document.getElementById('project-create-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            church_id: form.church_id.value || null,
            leader_id: form.leader_id.value || null,
            name: form.name.value,
            description: form.description.value,
            budget: form.budget.value || null,
            start_date: form.start_date.value || null,
            end_date: form.end_date.value || null,
            progress: form.progress.value || 0,
            status: form.status.value,
        };
        await AdminApp.apiPost('/api/v1/projects', payload);
        window.location.href = '/admin/projects';
    });
</script>
@endpush
