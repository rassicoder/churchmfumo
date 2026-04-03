@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="dashboard-loading align-items-center gap-2 mb-3" id="dashboardLoading">
    <div class="spinner-border spinner-border-sm" role="status"></div>
    <div>Loading dashboard...</div>
</div>
<div class="alert alert-warning d-none" id="dashboardError"></div>

<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted small">Enterprise snapshot</div>
        <button class="btn btn-outline-secondary btn-sm" id="dashboardRefresh">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3" id="totalChurchesCard">
                <x-stat-card title="Total Churches" value="--" value-id="totalChurches" icon="bi-buildings" trend="Live count across associations" />
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card title="Total Leaders" value="--" value-id="totalLeaders" icon="bi-people" trend="Leadership roster" />
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card title="Total Projects" value="--" value-id="totalProjects" icon="bi-kanban" trend="Active initiatives" />
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card title="Total Meetings" value="--" value-id="totalMeetings" icon="bi-calendar-event" trend="Scheduled this year" />
            </div>
        </div>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <x-chart-card
                    title="Church Performance Comparison"
                    subtitle="Analytics"
                    canvas-id="churchPerformanceChart"
                >
                    <x-slot:action>
                        <button class="btn btn-accent btn-sm">Export</button>
                    </x-slot:action>
                </x-chart-card>
            </div>
            <div class="col-12 col-lg-5">
                <x-chart-card
                    title="Monthly Financial Trend"
                    subtitle="Finance"
                    canvas-id="financialTrendChart"
                >
                    <x-slot:action>
                        <button class="btn btn-primary btn-sm">View</button>
                    </x-slot:action>
                </x-chart-card>
            </div>
        </div>
    </div>

    <div class="section-gap">
        <div class="row g-4">
            <div class="col-12 col-xl-6">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 heading">Recent Activity</h6>
                        <button class="btn btn-outline-secondary btn-sm">View All</button>
                    </div>
                    <div class="d-flex flex-column gap-3" id="activityFeed">
                        <div class="text-muted small">Loading activity...</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 heading">Alerts & Notifications</h6>
                        <button class="btn btn-outline-secondary btn-sm">Resolve</button>
                    </div>
                    <div class="d-flex flex-column gap-2" id="alertsFeed">
                        <div class="text-muted small">Loading alerts...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-gap">
    <div class="row g-4">
        <div class="col-12">
            <x-table
                title="Leaders Table"
                :headers="['Name', 'Position', 'Church', 'Status', 'Actions']"
                search-placeholder="Search leaders"
            >
                <x-slot:actions>
                    <button class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Leader</button>
                </x-slot:actions>
                <tr>
                    <td>Sarah K.</td>
                    <td>Secretary</td>
                    <td>Grace Chapel</td>
                    <td><span class="badge text-bg-success badge-status">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Joseph M.</td>
                    <td>Department Leader</td>
                    <td>St. Mark Church</td>
                    <td><span class="badge text-bg-warning badge-status">Pending</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Grace T.</td>
                    <td>Treasurer</td>
                    <td>Hope Assembly</td>
                    <td><span class="badge text-bg-danger badge-status">Overdue</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </x-table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/dashboard.js"></script>
@endpush
