<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - Church Association Leadership & Activities Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fb;
            --card: #ffffff;
            --ink: #0b1020;
            --muted: #5f6b7a;
            --line: #e5e9f2;
            --primary: #2b4eff;
            --accent: #f5b400;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #dc2626;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.05);
            --radius: 16px;
        }
        :root[data-theme="dark"] {
            --bg: #0b1220;
            --card: #0f172a;
            --ink: #e2e8f0;
            --muted: #94a3b8;
            --line: #1f2937;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg);
            color: var(--ink);
            font-family: "Inter", sans-serif;
            letter-spacing: 0.1px;
        }
        h1, h2, h3, h4, h5, h6, .heading { font-weight: 600; }

        .sidebar {
            background: #edf1f7;
            color: #1d4ed8;
            width: 268px;
            border-right: 1px solid #d6deea;
            box-shadow: 4px 0 16px rgba(15, 23, 42, 0.08);
        }
        .sidebar .offcanvas-body { background: #edf1f7; }

        .sidebar .brand { color: #1d4ed8; font-weight: 700; letter-spacing: 0.6px; }
        .sidebar .panel-label { color: #3b82f6; font-size: 12px; letter-spacing: 0.6px; text-transform: uppercase; }

        .sidebar a { color: #1d4ed8; text-decoration: none; }
        .sidebar .nav {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
        }
        .sidebar .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 12px;
            font-weight: 500;
            color: #1d4ed8;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .sidebar .nav-link i { color: #2563eb; }
        .sidebar .nav-link.active i, .sidebar .nav-link:hover i { color: #1d4ed8; }
        .sidebar .nav-link.active {
            background: #e5edff;
            color: #1d4ed8;
            box-shadow: inset 0 0 0 1px #c7d6ff;
        }
        .sidebar .nav-link:hover {
            color: #1d4ed8;
            background: #eef3ff;
        }

        .sidebar .offcanvas-header {
            background: #edf1f7;
            border-bottom: 1px solid #d6deea !important;
            padding: 20px 16px;
        }

        .topbar {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft);
        }
        :root[data-theme="dark"] .topbar {
            background: #0f172a;
        }
        .topbar .page-title { font-size: 18px; font-weight: 600; }
        .card {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            background: var(--card);
        }
        :root[data-theme="dark"] .card {
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }
        .badge-status { font-weight: 600; border-radius: 999px; }
        .table thead th { color: var(--muted); font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.6px; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-accent { background: var(--accent); border-color: var(--accent); color: #111827; }
        .content-wrapper { padding: 32px; }
        .section-gap { margin-bottom: 28px; }
        .icon-pill {
            width: 44px; height: 44px; border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(43, 78, 255, 0.12); color: var(--primary);
        }
        .toast-stack { z-index: 1080; }
        .toast-message {
            background: var(--card);
            color: var(--ink);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 240px;
        }
        .toast-message .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .toast-message.toast-success .dot { background: var(--success); }
        .toast-message.toast-error .dot { background: var(--danger); }
        .toast-message.toast-info .dot { background: var(--primary); }
        .dashboard-loading {
            display: none;
            background: var(--card);
            border: 1px dashed var(--line);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--muted);
        }
        body.is-loading .dashboard-loading { display: flex; }
        body.is-loading .dashboard-content { opacity: 0.5; pointer-events: none; }
        .chart-shell {
            min-height: 240px;
            position: relative;
        }
        .chart-shell .chart-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            border-radius: 12px;
            background: linear-gradient(90deg, rgba(43,78,255,0.08), rgba(43,78,255,0.02));
        }
        @media (max-width: 1023.98px) {
            .topbar { flex-wrap: wrap; gap: 10px; }
            .topbar .page-title { width: 100%; margin-top: 8px; }
        }
        @media (max-width: 767.98px) {
            .card.p-4 { padding: 20px !important; }
            .modal-dialog { max-width: 95%; margin: 0.5rem auto; }
            .btn { padding: 10px 14px; }
            .table-responsive { overflow-x: auto; }
        }
        @media (max-width: 991.98px) {
            .content-wrapper { padding: 16px; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="d-flex">
    <div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="adminSidebar">

        <div class="offcanvas-header border-bottom border-light-subtle">
            <div>
                <div class="brand fs-5">ChurchSystem</div>
                <div class="panel-label">CA Enterprise Panel</div>
            </div>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <nav class="nav flex-column gap-1">
                <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.churches') ? 'active' : ''); ?>" href="<?php echo e(route('admin.churches')); ?>">
                    <i class="bi bi-buildings"></i> Churches
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.leaders') ? 'active' : ''); ?>" href="<?php echo e(route('admin.leaders')); ?>">
                    <i class="bi bi-people"></i> Leaders
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.departments') ? 'active' : ''); ?>" href="<?php echo e(route('admin.departments')); ?>">
                    <i class="bi bi-diagram-3"></i> Departments
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.meetings') ? 'active' : ''); ?>" href="<?php echo e(route('admin.meetings')); ?>">
                    <i class="bi bi-calendar-event"></i> Meetings
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.projects') ? 'active' : ''); ?>" href="<?php echo e(route('admin.projects')); ?>">
                    <i class="bi bi-kanban"></i> Projects
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.finance') ? 'active' : ''); ?>" href="<?php echo e(route('admin.finance')); ?>">
                    <i class="bi bi-cash-coin"></i> Finance
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.reports') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports')); ?>">
                    <i class="bi bi-graph-up-arrow"></i> Reports
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings')); ?>">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </nav>
        </div>
    </div>

    <main class="flex-grow-1">
        <div class="content-wrapper">
            <nav class="navbar topbar px-3 mb-4">
                <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="ms-2 page-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="input-group d-none d-md-flex" style="max-width: 260px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search">
                    </div>
                    <button class="btn btn-outline-secondary" type="button" id="themeToggle" title="Toggle theme">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <button class="btn btn-light position-relative">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> Admin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.settings')); ?>">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item" type="button" onclick="AdminApp.logout()">Logout</button></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</div>
<div class="toast-stack position-fixed top-0 end-0 p-3" id="toastContainer"></div>

<script>
    (function () {
        const stored = localStorage.getItem('admin_theme') || 'light';
        document.documentElement.setAttribute('data-theme', stored);
    })();
</script>
<script src="/js/admin/app.js"></script>
<script>
    if (typeof AdminApp !== 'undefined') {
        AdminApp.ensureAuth();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/layouts/admin.blade.php ENDPATH**/ ?>