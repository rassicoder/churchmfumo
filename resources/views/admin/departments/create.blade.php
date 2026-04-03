@extends('layouts.admin')

@section('title', 'Create Department')
@section('page-title', 'Create Department')

@section('content')
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">New Department</h6>
        <form id="department-create-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Church</label>
                    <select class="form-select" name="church_id" id="department-church"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Leader</label>
                    <select class="form-select" name="leader_id" id="department-leader"></select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    Promise.all([
        AdminApp.initSelect({
            selectId: 'department-church',
            endpoint: '/api/v1/churches?per_page=100',
            valueKey: 'id',
            labelKey: 'name',
            placeholder: 'Select church'
        }),
        AdminApp.initSelect({
            selectId: 'department-leader',
            endpoint: '/api/v1/leaders?per_page=100',
            valueKey: 'id',
            labelKey: 'full_name',
            placeholder: 'Select leader'
        })
    ]).then(function () {
        AdminApp.getCurrentUser().then(function (profile) {
            const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
            if (role === 'Church Admin') {
                const select = document.getElementById('department-church');
                if (select) {
                    select.value = profile.church_id || '';
                    select.disabled = true;
                    const wrapper = select.closest('.col-md-6');
                    if (wrapper) wrapper.classList.add('d-none');
                }
            }
        });
    });

    document.getElementById('department-create-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            church_id: form.church_id.value || null,
            leader_id: form.leader_id.value || null,
            name: form.name.value,
        };
        await AdminApp.apiPost('/api/v1/departments', payload);
        window.location.href = '/admin/departments';
    });
</script>
@endpush
