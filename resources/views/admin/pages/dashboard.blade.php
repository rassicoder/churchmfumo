@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted">Total Churches</div>
                    <div class="fs-3 fw-bold" id="stat-churches">--</div>
                </div>
                <div class="icon rounded-circle d-flex align-items-center justify-content-center">C</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted">Total Leaders</div>
                    <div class="fs-3 fw-bold" id="stat-leaders">--</div>
                </div>
                <div class="icon rounded-circle d-flex align-items-center justify-content-center">L</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted">Active Projects</div>
                    <div class="fs-3 fw-bold" id="stat-projects">--</div>
                </div>
                <div class="icon rounded-circle d-flex align-items-center justify-content-center">P</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted">Overdue Actions</div>
                    <div class="fs-3 fw-bold" id="stat-overdue">--</div>
                </div>
                <div class="icon rounded-circle d-flex align-items-center justify-content-center">A</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-lg-7">
        <div class="card p-3">
            <div class="d-flex justify-content-between">
                <h6 class="mb-3">Monthly Financials</h6>
                <button class="btn btn-outline-secondary btn-sm">Export</button>
            </div>
            <div class="chart-box d-flex align-items-center justify-content-center text-muted">Chart Placeholder</div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card p-3">
            <h6 class="mb-3">Project Progress</h6>
            <div class="chart-box d-flex align-items-center justify-content-center text-muted">Chart Placeholder</div>
        </div>
    </div>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Recent Activity</h6>
        <button class="btn btn-outline-secondary btn-sm">View All</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="activity-table">
            <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Resource</th>
                <th>Timestamp</th>
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
    document.body.dataset.dashboard = 'association';
    AdminDashboard.loadDashboard();
    AdminDashboard.loadTable('/api/v1/admin/activity-logs', 'activity-table', function (row) {
        return '<tr>'
            + '<td>' + (row.user ? row.user.name : 'System') + '</td>'
            + '<td>' + row.action + '</td>'
            + '<td>' + row.table + '</td>'
            + '<td>' + row.created_at + '</td>'
            + '</tr>';
    });
</script>
@endpush
