@extends('admin.layouts.app')

@section('title', 'Meetings')
@section('page-title', 'Meetings')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Meetings</h6>
        <button class="btn btn-primary btn-sm">New Meeting</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="meetings-table">
            <thead>
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Church</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan="3" class="text-muted">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AdminDashboard.loadTable('/api/v1/meetings', 'meetings-table', function (row) {
        return '<tr>'
            + '<td>' + row.meeting_type + '</td>'
            + '<td>' + row.meeting_date + '</td>'
            + '<td>' + (row.church ? row.church.name : '-') + '</td>'
            + '</tr>';
    });
</script>
@endpush
