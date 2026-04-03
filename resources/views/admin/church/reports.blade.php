@extends('layouts.church-admin')
@section('active', 'reports')

@section('title', 'Church Reports')
@section('header_kicker', 'Reports')
@section('header_title', 'Church Analytics')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="text-sm text-slate-500">Total Leaders</div>
            <div id="reportLeaders" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="text-sm text-slate-500">Meetings</div>
            <div id="reportMeetings" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="text-sm text-slate-500">Projects</div>
            <div id="reportProjects" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="text-sm text-slate-500">Departments</div>
            <div id="reportDepartments" class="text-2xl font-semibold mt-2">--</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-slate-500">Finance Overview</div>
                <div class="text-lg font-semibold">Budget vs Expenses</div>
            </div>
        </div>
        <div class="mt-4">
            <canvas id="financeChart" height="120"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="/js/church-reports.js"></script>
@endpush
