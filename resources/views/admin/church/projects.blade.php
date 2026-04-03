@extends('layouts.church-admin')
@section('active', 'projects')

@section('title', 'Church Projects')
@section('header_kicker', 'Projects')
@section('header_title', 'Active Initiatives')
@section('header_right')
    <a href="/admin/church/projects/create" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm shadow">Create Project</a>
    <button data-logout class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Logout</button>
@endsection

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm text-slate-500">Projects</div>
                <div class="text-lg font-semibold">Current Projects</div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-slate-400">
                    <tr>
                        <th class="py-2">Name</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Budget</th>
                        <th class="py-2">Progress</th>
                    </tr>
                </thead>
                <tbody id="projectsList" class="text-slate-700">
                    <tr><td class="py-3" colspan="4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="projectsEmpty" class="text-sm text-slate-500 mt-3 hidden">No projects available.</div>
    </div>
@endsection

@push('scripts')
    <script src="/js/church-projects.js"></script>
@endpush
