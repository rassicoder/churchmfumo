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
