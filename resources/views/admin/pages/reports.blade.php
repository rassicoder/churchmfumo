@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="card p-3 mb-4">
    <div class="d-flex flex-wrap align-items-center gap-3">
        <div>
            <label class="form-label small text-muted">Date Range</label>
            <select class="form-select" id="reportRange">
                <option value="7d">Last 7 days</option>
                <option value="30d">Last 30 days</option>
                <option value="year" selected>This year</option>
            </select>
        </div>
        <div id="churchFilterWrap">
            <label class="form-label small text-muted">Church</label>
            <select class="form-select" id="reportChurch"></select>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="reportRefresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
            <button class="btn btn-outline-secondary btn-sm" id="reportExportCsv"><i class="bi bi-filetype-csv me-1"></i>Export CSV</button>
            <button class="btn btn-outline-secondary btn-sm" id="reportExportPdf"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</button>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <x-stat-card title="Total Leaders" value="--" value-id="reportLeaders" icon="bi-people" trend="Within selected range" />
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <x-stat-card title="Meetings" value="--" value-id="reportMeetings" icon="bi-calendar-event" trend="Meeting activity" />
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <x-stat-card title="Projects" value="--" value-id="reportProjects" icon="bi-kanban" trend="Active programs" />
    </div>
    <div class="col-12 col-md-6 col-xl-3" id="reportChurchesCard">
        <x-stat-card title="Churches" value="--" value-id="reportChurches" icon="bi-buildings" trend="Total churches" />
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <x-chart-card title="Leader Growth" subtitle="People" canvas-id="leaderGrowthChart" />
    </div>
    <div class="col-12 col-lg-6" id="churchGrowthCard">
        <x-chart-card title="Church Growth" subtitle="Coverage" canvas-id="churchGrowthChart" />
    </div>
    <div class="col-12">
        <x-chart-card title="Budget vs Expenses" subtitle="Finance" canvas-id="financeReportChart" />
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/reports.js"></script>
@endpush
