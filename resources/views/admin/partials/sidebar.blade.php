<aside class="col-12 col-lg-2 sidebar p-4">
    <div class="logo mb-4">ChurchSystem</div>
    <nav class="d-flex flex-column gap-2">
        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="{{ request()->routeIs('admin.churches') ? 'active' : '' }}" href="{{ route('admin.churches') }}">Churches</a>
        <a class="{{ request()->routeIs('admin.leaders') ? 'active' : '' }}" href="{{ route('admin.leaders') }}">Leaders</a>
        <a class="{{ request()->routeIs('admin.departments') ? 'active' : '' }}" href="{{ route('admin.departments') }}">Departments</a>
        <a class="{{ request()->routeIs('admin.meetings') ? 'active' : '' }}" href="{{ route('admin.meetings') }}">Meetings</a>
        <a class="{{ request()->routeIs('admin.projects') ? 'active' : '' }}" href="{{ route('admin.projects') }}">Projects</a>
        <a class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">Reports</a>
    </nav>
</aside>
