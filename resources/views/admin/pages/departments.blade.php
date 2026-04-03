@extends('admin.layouts.app')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Departments</h6>
        <button class="btn btn-primary btn-sm">New Department</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="departments-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Church</th>
                <th>Leader</th>
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
    AdminDashboard.loadTable('/api/v1/departments', 'departments-table', function (row) {
        return '<tr>'
            + '<td>' + row.name + '</td>'
            + '<td>' + (row.church ? row.church.name : '-') + '</td>'
            + '<td>' + (row.leader ? row.leader.full_name : '-') + '</td>'
            + '</tr>';
    });
</script>
@endpush
