@extends('layouts.admin')

@section('title', 'Create Meeting')
@section('page-title', 'Create Meeting')

@section('content')
@php
    $types = config('meeting.meeting_types', ['general', 'leadership']);
@endphp
<div class="section-gap">
    <div class="card p-4">
        <h6 class="mb-3 heading">New Meeting</h6>
        <form id="meeting-create-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Church</label>
                    <select class="form-select" name="church_id" id="meeting-church"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meeting Type</label>
                    <select class="form-select" name="meeting_type">
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meeting Date</label>
                    <input type="date" class="form-control" name="meeting_date" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Agenda</label>
                    <textarea class="form-control" name="agenda" rows="3"></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Minutes</label>
                    <textarea class="form-control" name="minutes" rows="4"></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ route('admin.meetings') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AdminApp.initSelect({
        selectId: 'meeting-church',
        endpoint: '/api/v1/churches?per_page=100',
        valueKey: 'id',
        labelKey: 'name',
        placeholder: 'Select church'
    }).then(function () {
        AdminApp.getCurrentUser().then(function (profile) {
            const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
            if (role === 'Church Admin') {
                const select = document.getElementById('meeting-church');
                if (select) {
                    select.value = profile.church_id || '';
                    select.disabled = true;
                    const wrapper = select.closest('.col-md-6');
                    if (wrapper) wrapper.classList.add('d-none');
                }
            }
        });
    });

    document.getElementById('meeting-create-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const payload = {
            church_id: form.church_id.value,
            meeting_type: form.meeting_type.value,
            meeting_date: form.meeting_date.value,
            agenda: form.agenda.value,
            minutes: form.minutes.value,
        };
        await AdminApp.apiPost('/api/v1/meetings', payload);
        window.location.href = '/admin/meetings';
    });
</script>
@endpush
