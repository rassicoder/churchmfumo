@extends('layouts.admin')

@section('title', 'Create Church')
@section('page-title', 'Create Church')

@section('content')
@php
    $statuses = config('church.statuses', ['active', 'inactive']);
@endphp
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">New Church</h6>
        <form id="church-create-form">
            <div class="row g-3">
                <div class="col-12">
                    <div class="fw-semibold text-muted small text-uppercase">Church Information</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" name="location" required>
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
                    <label class="form-label">Pastor</label>
                    <select class="form-select" name="pastor_id" id="church-pastor"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @if($status === 'active') selected @endif>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-2">
                    <div class="fw-semibold text-muted small text-uppercase">Admin Information</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Name</label>
                    <input type="text" class="form-control" name="admin_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Email</label>
                    <input type="email" class="form-control" name="admin_email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Password</label>
                    <input type="password" class="form-control" name="admin_password" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ route('admin.churches') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AdminApp.initSelect({
        selectId: 'church-pastor',
        endpoint: '/api/v1/leaders?per_page=100',
        valueKey: 'id',
        labelKey: 'full_name',
        placeholder: 'Select pastor'
    });

    document.getElementById('church-create-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            name: form.name.value,
            location: form.location.value,
            phone: form.phone.value,
            email: form.email.value,
            pastor_id: form.pastor_id.value || null,
            status: form.status.value,
            admin_name: form.admin_name.value,
            admin_email: form.admin_email.value,
            admin_password: form.admin_password.value,
        };
        await AdminApp.apiPost('/api/v1/churches', payload);
        window.location.href = '/admin/churches';
    });
</script>
@endpush
