@extends('admin.layouts.app')

@section('title', 'Leaders')
@section('page-title', 'Leaders')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Leaders</h6>
        <button class="btn btn-primary btn-sm">New Leader</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="leaders-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Level</th>
                <th>Status</th>
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
    AdminDashboard.loadTable('/api/v1/leaders', 'leaders-table', function (row) {
        return '<tr>'
            + '<td>' + row.full_name + '</td>'
            + '<td>' + row.position + '</td>'
            + '<td>' + row.level + '</td>'
            + '<td>' + row.status + '</td>'
            + '</tr>';
    });
</script>
@endpush
