@extends('layouts.church-admin')
@section('active', 'dashboard')

@section('title', 'Church Admin Dashboard')
@section('header_kicker', 'Church')
@section('header_title')
    <div id="churchName" class="text-lg font-semibold text-slate-900">Loading...</div>
@endsection
@section('header_right')
    <div class="text-right hidden sm:block">
        <div class="text-sm text-slate-500">Welcome back,</div>
        <div id="welcomeName" class="text-sm font-semibold text-slate-800">Admin</div>
    </div>
    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600">A</div>
    <button data-logout class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Logout</button>
@endsection
@section('main_class', 'px-6 py-8 space-y-8')

@section('content')
    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4" id="statsSection">
        <div class="glass-card rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Total Leaders</div>
            <div id="statLeaders" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="glass-card rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Upcoming Meetings</div>
            <div id="statMeetings" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="glass-card rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Active Projects</div>
            <div id="statProjects" class="text-2xl font-semibold mt-2">--</div>
        </div>
        <div class="glass-card rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Departments</div>
            <div id="statDepartments" class="text-2xl font-semibold mt-2">--</div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="col-span-1 lg:col-span-2 glass-card rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-500">Quick Actions</div>
                    <div class="text-lg font-semibold">What would you like to do?</div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5">
                <a href="/admin/church/leaders/create" class="rounded-2xl border border-slate-200 p-4 hover:shadow-md transition">
                    <div class="text-sm text-slate-500">Add Leader</div>
                    <div class="font-semibold">Create profile</div>
                </a>
                <a href="/admin/church/meetings/create" class="rounded-2xl border border-slate-200 p-4 hover:shadow-md transition">
                    <div class="text-sm text-slate-500">Schedule Meeting</div>
                    <div class="font-semibold">Plan meeting</div>
                </a>
                <a href="/admin/church/projects/create" class="rounded-2xl border border-slate-200 p-4 hover:shadow-md transition">
                    <div class="text-sm text-slate-500">Create Project</div>
                    <div class="font-semibold">Launch project</div>
                </a>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Activity Feed</div>
            <div id="activityFeed" class="mt-4 space-y-3 text-sm text-slate-700">
                <div class="text-slate-500">Loading activity...</div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-500">Leaders</div>
                    <div class="text-lg font-semibold">Recent Leaders</div>
                </div>
                <a href="/admin/church/leaders" class="text-sm text-blue-600">View all</a>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-400">
                        <tr>
                            <th class="py-2">Name</th>
                            <th class="py-2">Position</th>
                            <th class="py-2">Phone</th>
                        </tr>
                    </thead>
                    <tbody id="leadersTable" class="text-slate-700">
                        <tr><td class="py-3" colspan="3">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-500">Meetings</div>
                    <div class="text-lg font-semibold">Upcoming Meetings</div>
                </div>
                <a href="/admin/church/meetings" class="text-sm text-blue-600">View all</a>
            </div>
            <div id="meetingsList" class="mt-4 space-y-3 text-sm text-slate-700">
                <div class="text-slate-500">Loading meetings...</div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="/js/church-dashboard.js"></script>
@endpush
