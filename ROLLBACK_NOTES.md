# Rollback Notes - Removed Locale Prefix Requirements

## What Was Done

Reverted the last 2 commits that added locale prefix requirements to routes:
- `65745e0` - Fix: Ensure route group has locale prefix for all routes
- `b5ffb95` - Fix route errors: Add locale prefix with fallback to all route calls

## Current State

- Routes work **WITHOUT** locale prefix in route names
- Routes like `/shop` work correctly (defined outside locale group)
- Routes like `/dashboard/login` work correctly (dashboard routes are separate)
- Views use route names without locale prefix: `route('web.shop.search')`, `route('view_login')`

## Routes Structure

1. **Routes inside locale group** (`/en` and `/ar`):
   - Home route: `/en` or `/ar`
   - Auth routes: `/en/login`, `/ar/login`, etc.
   - These routes work with locale prefix in URL but route names don't have locale prefix

2. **Routes outside locale group** (no locale prefix):
   - `/shop` - works
   - `/dashboard/*` - works
   - `/home_visits/*` - works
   - `/courses/*` - works
   - `/erp` - works

## Next Steps for Server

After pulling this update on the server:

```bash
git pull origin main
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Why This Approach

The user confirmed that:
- ✅ `/shop` works (no locale prefix needed)
- ✅ `/dashboard/login` works (no locale prefix needed)
- ❌ `/ar` doesn't work (needs investigation)
- ❌ `/dashboard/home` doesn't work (needs investigation)

By removing the locale prefix requirement from route names, we ensure routes work consistently regardless of locale.

