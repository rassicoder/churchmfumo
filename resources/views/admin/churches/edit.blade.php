@extends('layouts.admin')

@section('title', 'Edit Church')
@section('page-title', 'Edit Church')

@section('content')
@php
    $statuses = config('church.statuses', ['active', 'inactive']);
@endphp
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">Edit Church</h6>
        <form id="church-edit-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" name="location">
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
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="{{ route('admin.churches') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const churchId = "{{ request()->route('id') }}";

    AdminApp.initSelect({
        selectId: 'church-pastor',
        endpoint: '/api/v1/leaders?per_page=100',
        valueKey: 'id',
        labelKey: 'full_name',
        placeholder: 'Select pastor'
    }).then(function () {
        AdminApp.apiGet('/api/v1/churches/' + churchId).then(function (data) {
            const church = data.data;
            const form = document.getElementById('church-edit-form');
            form.name.value = church.name || '';
            form.location.value = church.location || '';
            form.phone.value = church.phone || '';
            form.email.value = church.email || '';
            form.pastor_id.value = church.pastor_id || '';
            form.status.value = church.status || '';
        });
    });

    document.getElementById('church-edit-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            name: form.name.value,
            location: form.location.value,
            phone: form.phone.value,
            email: form.email.value,
            pastor_id: form.pastor_id.value || null,
            status: form.status.value,
        };
        await AdminApp.apiPut('/api/v1/churches/' + churchId, payload);
        window.location.href = '/admin/churches';
    });
</script>
@endpush
