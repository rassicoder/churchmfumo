@extends('layouts.admin')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')
<div class="section-gap">
    <x-table
        title="Projects"
        :headers="['Project', 'Leader', 'Progress', 'Status', 'Actions']"
        search-placeholder="Search projects"
        table-id="projects-table"
    >
        <x-slot:actions>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.projects.create') }}"><i class="bi bi-plus-circle me-1"></i> Add Project</a>
        </x-slot:actions>
        <tr><td colspan="5" class="text-muted">Loading...</td></tr>
    </x-table>
</div>
@endsection

@push('scripts')
<script>
    const projectsTable = AdminApp.initTable({
        tableId: 'projects-table',
        endpoint: '/api/v1/projects',
        mapRow: function (row) {
            const leader = row.leader ? row.leader.full_name : '-';
            return '<tr>'
                + '<td>' + (row.name || '-') + '</td>'
                + '<td>' + leader + '</td>'
                + '<td>' + (row.progress != null ? row.progress + '%' : '-') + '</td>'
                + '<td>' + AdminApp.statusBadge(row.status) + '</td>'
                + '<td>'
                + '<div class="dropdown">'
                + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                + '<ul class="dropdown-menu dropdown-menu-end">'
                + '<li><a class="dropdown-item" href="/admin/projects/' + row.id + '/edit">Edit</a></li>'
                + '<li><button class="dropdown-item text-danger" type="button" onclick="deleteProject(\'' + row.id + '\')">Delete</button></li>'
                + '</ul>'
                + '</div>'
                + '</td>'
                + '</tr>';
        }
    });

    async function deleteProject(id) {
        if (!confirm('Delete this project?')) return;
        await AdminApp.apiDelete('/api/v1/projects/' + id);
        projectsTable.reload();
    }
    window.deleteProject = deleteProject;
</script>
@endpush
