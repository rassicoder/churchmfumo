# Production Deployment Checklist

## Environment
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Set `APP_URL=https://your-domain.com`
- Configure database settings in `.env`:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=...`
  - `DB_PORT=3306`
  - `DB_DATABASE=...`
  - `DB_USERNAME=...`
  - `DB_PASSWORD=...`

## Optimize Laravel
Run the following on the server after deploying:

```bash
php artisan config:cache
php artisan route:cache
php artisan optimize
```

## Storage & Uploads
Create the public storage symlink:

```bash
php artisan storage:link
```

Ensure the web server can write to:
- `storage/`
- `bootstrap/cache/`

## Security
- Confirm all API routes use `auth:sanctum`
- Disable debug in production
- Use HTTPS and set proper `APP_URL`

## Notes
- The admin UI uses API-first CRUD (AJAX) for churches and leaders.
- 401 responses redirect to `/admin/login`.
- 403 responses surface “Unauthorized” in the UI.

## Railway 500 Checklist
- Temporarily set `APP_DEBUG=true` in Railway variables to expose the real exception.
- Set `LOG_CHANNEL=stderr` so Laravel logs appear in Railway deployment/runtime logs.
- Confirm `APP_KEY` is set in Railway variables.
- Confirm `APP_URL` matches the generated Railway domain or your custom domain.
- Confirm database variables are correct:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=...`
  - `DB_PORT=3306`
  - `DB_DATABASE=...`
  - `DB_USERNAME=...`
  - `DB_PASSWORD=...`
- If you use Sanctum with a browser frontend, set:
  - `SESSION_DOMAIN=your-domain`
  - `SANCTUM_STATEFUL_DOMAINS=your-domain`
- Railway’s Laravel startup already runs migrations, creates the storage symlink, and optimizes caches by default.
- After the issue is fixed, set `APP_DEBUG=false` again.
