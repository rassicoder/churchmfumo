@extends('admin.layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Projects</h6>
        <button class="btn btn-primary btn-sm">New Project</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="projects-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Start</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan="4" class="text-muted">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AdminDashboard.loadTable('/api/v1/projects', 'projects-table', function (row) {
        return '<tr>'
            + '<td>' + row.name + '</td>'
            + '<td>' + row.status + '</td>'
            + '<td>' + row.progress + '%</td>'
            + '<td>' + row.start_date + '</td>'
            + '</tr>';
    });
</script>
@endpush
