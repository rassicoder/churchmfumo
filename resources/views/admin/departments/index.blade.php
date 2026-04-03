@extends('layouts.admin')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<div class="section-gap">
    <x-table
        title="Departments"
        :headers="['Department', 'Church', 'Leader', 'Status', 'Actions']"
        search-placeholder="Search departments"
        table-id="departments-table"
    >
        <x-slot:actions>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.departments.create') }}"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
        </x-slot:actions>
        <tr><td colspan="5" class="text-muted">Loading...</td></tr>
    </x-table>
</div>
@endsection

@push('scripts')
<script>
    const departmentsTable = AdminApp.initTable({
        tableId: 'departments-table',
        endpoint: '/api/v1/departments',
        mapRow: function (row) {
            const church = row.church ? row.church.name : '-';
            const leader = row.leader ? row.leader.full_name : '-';
            return '<tr>'
                + '<td>' + (row.name || '-') + '</td>'
                + '<td>' + church + '</td>'
                + '<td>' + leader + '</td>'
                + '<td>' + AdminApp.statusBadge(row.status || 'Active') + '</td>'
                + '<td>'
                + '<div class="dropdown">'
                + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                + '<ul class="dropdown-menu dropdown-menu-end">'
                + '<li><a class="dropdown-item" href="/admin/departments/' + row.id + '/edit">Edit</a></li>'
                + '<li><button class="dropdown-item text-danger" type="button" onclick="deleteDepartment(\'' + row.id + '\')">Delete</button></li>'
                + '</ul>'
                + '</div>'
                + '</td>'
                + '</tr>';
        }
    });

    async function deleteDepartment(id) {
        if (!confirm('Delete this department?')) return;
        await AdminApp.apiDelete('/api/v1/departments/' + id);
        departmentsTable.reload();
    }
    window.deleteDepartment = deleteDepartment;
</script>
@endpush
