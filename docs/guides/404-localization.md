Issue: public root redirected to /en which returned 404

Cause:
- The site used `laravel-localization` and `useAcceptLanguageHeader` which redirected `/` to `/en`.
- The routes were registered without the `en` prefix (default locale hidden), so `/en` had no route and returned 404.

Fix applied:
- Removed the top-level redirect that forced `/` → `/en`.
- Set `hideDefaultLocaleInURL` to `true` in `config/laravellocalization.php` so default locale URLs are served at `/`.
- Added a route to redirect legacy `/en` URLs to `/` to avoid 404s.

Deployment steps:
1. Deploy the code change.
2. Run `php artisan config:clear && php artisan route:clear && php artisan cache:clear`.
3. Restart PHP-FPM / web server if needed.
4. Ensure the web server `DocumentRoot` points to the `public` directory.

If you prefer showing the default locale in URLs, change `hideDefaultLocaleInURL` back to `false` and ensure routes are registered with locale prefixes (adjust routes and middleware accordingly).