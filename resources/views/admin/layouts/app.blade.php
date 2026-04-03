<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - ChurchSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f6fb;
            --card: #ffffff;
            --ink: #101828;
            --muted: #667085;
            --line: #e4e7ec;
            --primary: #2563eb;
        }
        body { background: var(--bg); color: var(--ink); }
        .sidebar {
            min-height: 100vh;
            background: #0b1220;
            color: #cbd5e1;
        }
        .sidebar a { color: #cbd5e1; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { color: #fff; }
        .sidebar .logo { color: #fff; font-weight: 700; letter-spacing: 0.5px; }
        .card { border: 1px solid var(--line); border-radius: 12px; }
        .card .icon { width: 36px; height: 36px; background: #e7efff; color: var(--primary); }
        .chart-box {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 12px;
            height: 220px;
        }
        .table thead th { color: var(--muted); font-weight: 600; }
        .page-title { font-size: 20px; font-weight: 700; }
        @media (max-width: 991.98px) {
            .sidebar { min-height: auto; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include('admin.partials.sidebar')
        <main class="col-12 col-lg-10 p-4">
            @include('admin.partials.navbar')
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/admin/app.js"></script>
@stack('scripts')
</body>
</html>
