# ChurchSystem API (Laravel 10)

API-first starter for a Church Association Leadership & Activities Management System.

## Stack

- Laravel 10
- Sanctum (API auth)
- MySQL
- Spatie Laravel Permission
- UUID primary keys
- Soft deletes
- Repository/Service pattern

## Install

1. `composer install`
2. `cp .env.example .env`
3. Update DB credentials in `.env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan serve`

## API Base

- `/api/v1`

## Example Endpoints

- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`
- `GET /api/v1/auth/me` (Sanctum token)
- `POST /api/v1/auth/logout` (Sanctum token)

## Architecture

- `app/Repositories/Contracts` for interfaces
- `app/Repositories/Eloquent` for DB implementations
- `app/Services` for business logic
- `app/Http/Controllers/API` for API controllers
- `database/migrations` includes UUID + soft delete schema

## Mobile Ready

- Token-based auth via Sanctum
- Versioned API routes (`v1`)
- Stateless JSON responses
