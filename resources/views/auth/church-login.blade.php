<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Church Admin Login - Church Association Leadership & Activities Management System</title>
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
        <h4 class="mb-1">Church Admin Login</h4>
        <p class="text-muted mb-4">Sign in to manage your church dashboard.</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
        @endif
        <form method="POST" action="{{ route('church.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="login-email">Email</label>
                <input type="email" class="form-control" id="login-email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="login-password">Password</label>
                <input type="password" class="form-control" id="login-password" name="password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Sign In</button>
        </form>
    </div>
</div>
</body>
</html>
