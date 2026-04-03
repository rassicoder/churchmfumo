@extends('admin.layouts.app')

@section('title', 'Churches')
@section('page-title', 'Churches')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Churches</h6>
        <button class="btn btn-primary btn-sm">New Church</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="churches-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Status</th>
                <th>Created</th>
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
    AdminDashboard.loadTable('/api/v1/churches', 'churches-table', function (row) {
        return '<tr>'
            + '<td>' + row.name + '</td>'
            + '<td>' + (row.location || '-') + '</td>'
            + '<td>' + row.status + '</td>'
            + '<td>' + (row.created_at || '-') + '</td>'
            + '</tr>';
    });
</script>
@endpush
