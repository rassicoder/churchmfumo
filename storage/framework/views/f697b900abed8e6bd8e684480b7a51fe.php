<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Church Association Leadership & Activities Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fb;
            --card: #ffffff;
            --ink: #0b1020;
            --muted: #5f6b7a;
            --primary: #2b4eff;
        }
        body { background: var(--bg); color: var(--ink); }
        .login-card {
            max-width: 420px;
            border-radius: 16px;
            border: 1px solid #e5e9f2;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="card p-4 login-card w-100">
        <h4 class="mb-1">Admin Login</h4>
        <p class="text-muted mb-4">Sign in to manage the system.</p>
        <div id="login-error" class="alert alert-danger d-none"></div>
        <form id="login-form">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" id="login-email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="login-password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Sign In</button>
        </form>
    </div>
</div>

<script src="/js/admin/app.js"></script>
<script>
    (function () {
        const form = document.getElementById('login-form');
        const errorBox = document.getElementById('login-error');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            errorBox.classList.add('d-none');
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            try {
                const profile = await AdminApp.login(email, password);
                const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
                if (String(role || '').toLowerCase() === 'church admin') {
                    window.location.href = '/admin/church-dashboard';
                } else {
                    window.location.href = '/admin/dashboard';
                }
            } catch (err) {
                errorBox.textContent = err.message || 'Login failed';
                errorBox.classList.remove('d-none');
            }
        });
    })();
</script>
</body>
</html>
<?php /**PATH /home/rasi-sudi/Documents/churchsystem/resources/views/admin/login.blade.php ENDPATH**/ ?>