<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signing In...</title>
</head>
<body>
<p>Signing you in...</p>

<script>
    (function () {
        localStorage.removeItem('api_token');
        localStorage.removeItem('current_user');
        localStorage.removeItem('user');

        localStorage.setItem('api_token', @json($token));
        localStorage.setItem('current_user', JSON.stringify({
            user: @json($user),
            role: @json($role),
            roles: [@json($role)],
            church_id: @json($user->church_id),
        }));

        window.location.replace(@json($redirectPath));
    })();
</script>
</body>
</html>
