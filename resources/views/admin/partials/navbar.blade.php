<nav class="navbar bg-white rounded-3 px-3 mb-4 shadow-sm">
    <span class="navbar-brand mb-0 h1">@yield('page-title', 'Admin')</span>
    <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
            <button class="btn btn-outline-secondary btn-sm">Notifications</button>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Profile</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
