<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name')); ?></title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --ink: #122133;
            --muted: #5b6b7c;
            --line: #d8e1ec;
            --accent: #0d9488;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", "Trebuchet MS", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% -10%, #c9f3ee 0%, transparent 35%),
                radial-gradient(circle at 90% 0%, #dbe7ff 0%, transparent 30%),
                var(--bg);
        }
        .wrap {
            max-width: 980px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .hero, .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(18, 33, 51, 0.06);
        }
        .hero { padding: 24px; margin-bottom: 16px; }
        h1 { margin: 0 0 8px; font-size: 28px; }
        p { margin: 0; color: var(--muted); }
        .badge {
            display: inline-block;
            margin-top: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e7f7f5;
            color: #0f766e;
            border: 1px solid #b8ece7;
            font-size: 13px;
            font-weight: 600;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }
        .card { padding: 16px; }
        .card h2 { margin: 0 0 10px; font-size: 16px; }
        .card a {
            display: inline-block;
            margin: 4px 0;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        .card a:hover { text-decoration: underline; }
        code {
            background: #edf3fb;
            border: 1px solid #d8e1ec;
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <section class="hero">
        <h1><?php echo e(config('app.name')); ?></h1>
        <p>Church Association Leadership & Activities Management System</p>
        <div class="badge">API-first • Laravel 10 • Sanctum</div>
    </section>

    <section class="grid">
        <article class="card">
            <h2>Health</h2>
            <a href="/health">GET /health</a><br>
            <small>Quick runtime status endpoint</small>
        </article>
        <article class="card">
            <h2>Auth API</h2>
            <code>/api/v1/auth/login</code><br>
            <code>/api/v1/auth/register</code>
        </article>
        <article class="card">
            <h2>Core Modules</h2>
            <code>/api/v1/churches</code><br>
            <code>/api/v1/leaders</code><br>
            <code>/api/v1/departments</code><br>
            <code>/api/v1/meetings</code>
        </article>
    </section>
</div>
</body>
</html>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/home.blade.php ENDPATH**/ ?>