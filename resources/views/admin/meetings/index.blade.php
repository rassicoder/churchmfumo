@extends('layouts.admin')

@section('title', 'Meetings')
@section('page-title', 'Meetings')

@section('content')
<div class="section-gap">
    <x-table
        title="Meetings"
        :headers="['Meeting', 'Church', 'Date', 'Status', 'Actions']"
        search-placeholder="Search meetings"
        table-id="meetings-table"
    >
        <x-slot:actions>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.meetings.create') }}"><i class="bi bi-plus-circle me-1"></i> Add Meeting</a>
        </x-slot:actions>
        <tr><td colspan="5" class="text-muted">Loading...</td></tr>
    </x-table>
</div>
@endsection

@push('scripts')
<script>
    const meetingsTable = AdminApp.initTable({
        tableId: 'meetings-table',
        endpoint: '/api/v1/meetings',
        mapRow: function (row) {
            const church = row.church ? row.church.name : '-';
            return '<tr>'
                + '<td>' + (row.meeting_type || '-') + '</td>'
                + '<td>' + church + '</td>'
                + '<td>' + (row.meeting_date || '-') + '</td>'
                + '<td>' + AdminApp.statusBadge(row.status || 'Active') + '</td>'
                + '<td>'
                + '<div class="dropdown">'
                + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                + '<ul class="dropdown-menu dropdown-menu-end">'
                + '<li><a class="dropdown-item" href="/admin/meetings/' + row.id + '/edit">Edit</a></li>'
                + '<li><button class="dropdown-item text-danger" type="button" onclick="deleteMeeting(\'' + row.id + '\')">Delete</button></li>'
                + '</ul>'
                + '</div>'
                + '</td>'
                + '</tr>';
        }
    });

    async function deleteMeeting(id) {
        if (!confirm('Delete this meeting?')) return;
        await AdminApp.apiDelete('/api/v1/meetings/' + id);
        meetingsTable.reload();
    }
    window.deleteMeeting = deleteMeeting;
</script>
@endpush
