# Deployment Cache And Permissions SOP

Use this checklist for every development and production deployment. It prevents stale Laravel caches, unreadable environment configuration, broken Hashid routes, and runtime permission failures.

## 1. Confirm The Target

```bash
cd /var/www/archerkids
hostname
git status --short --branch
git rev-parse --short HEAD
git fetch origin
```

Expected branches:

- Development server: `development_1`
- Production server: `main`

Do not deploy with unreviewed tracked changes in the server worktree. Do not delete untracked `.env` backups or operational files.

## 2. Check Environment Permissions

Never print `.env` contents during deployment. Check only ownership and readability.

```bash
stat -c '%A %U:%G %n' .env storage bootstrap/cache
test -r .env && echo 'deployment user can read .env'
sudo -u www-data test -r .env \
  && echo 'www-data can read .env' \
  || echo 'www-data cannot read .env'
```

The deployment account must read `.env`. If configuration remains uncached, `www-data` must also read it. A normal uncached setup is:

```bash
sudo chown ubuntu:www-data .env
sudo chmod 640 .env
```

Never use `chmod 777` and never expose `.env` through the web server.

## 3. Check Runtime Directories

Laravel must write to `storage` and `bootstrap/cache`.

```bash
sudo chown -R ubuntu:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

Recheck these paths after Composer, Artisan, or frontend build commands because newly generated files may have different ownership.

## 4. Update The Code

Use a fast-forward pull so the server cannot create an accidental merge commit.

```bash
git pull --ff-only origin <branch>
git rev-parse --short HEAD
git log -1 --oneline
```

When `composer.lock` changed:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
```

When frontend source or lock files changed:

```bash
npm ci
npm run build
```

## 5. Run Approved Migrations

```bash
php artisan migrate:status
php artisan migrate --force
```

Run `migrate --force` only when the release contains approved migrations. Take a database backup before destructive or high-risk schema changes.

## 6. Clear Laravel Caches

This step is mandatory after every deployment.

```bash
sudo -u www-data php artisan optimize:clear
```

### Project-specific route-cache rule

Do **not** run `php artisan route:cache` on this project. Custom Hashid bindings are registered while route files load. Cached routes skip that registration and cause valid URLs such as `/admin/students/{hash}` to return `404`.

Confirm that no route cache remains:

```bash
test ! -f bootstrap/cache/routes-v7.php \
  && echo 'route cache absent' \
  || echo 'ERROR: route cache still present'
```

Configuration cache is optional. If required, build it only as an account that can read `.env`:

```bash
php artisan config:cache
php artisan about --only=environment
```

Never build configuration cache as `www-data` when `.env` is unreadable by `www-data`. That can cache an empty `APP_KEY` and default database credentials. If uncertain, leave configuration uncached after `optimize:clear`.

View cache is safe when runtime permissions are correct:

```bash
sudo -u www-data php artisan view:cache
```

## 7. Reload The Correct Services

Do not assume development and production use the same PHP version.

```bash
systemctl list-units --type=service --state=running --no-legend \
  | grep -E 'php.*fpm'
sudo systemctl reload php8.x-fpm
sudo nginx -t
sudo systemctl reload nginx
systemctl is-active php8.x-fpm nginx
```

Current observed versions:

- Development: PHP 8.3-FPM
- Production: PHP 8.2-FPM

Always trust the service detected on the server if it has been upgraded.

## 8. Verify The Deployment

```bash
curl -sS -o /dev/null -w '%{http_code}\n' https://<host>/
git status --short --branch
git log -1 --oneline
```

The homepage should return `200`. Also test the feature changed by the release and an authenticated hashed route such as Student View/Edit.

For API changes, confirm the expected status. An authenticated endpoint returning `401` without credentials proves that the route and authentication middleware exist; a `404` does not.

Check recent logs:

```bash
tail -n 100 storage/logs/laravel.log
sudo tail -n 100 /var/log/nginx/error.log
```

## 9. Failure Checks

For a `500`, check these first:

1. `APP_KEY` and database configuration were not lost during `config:cache`.
2. `.env` is readable by the account loading uncached configuration.
3. `storage` and `bootstrap/cache` are writable by `www-data`.
4. The correct PHP-FPM service was reloaded.
5. Laravel and Nginx logs contain no new deployment exception.

If hashed View/Edit URLs return `404` while the list still shows the record:

```bash
sudo -u www-data php artisan route:clear
sudo systemctl reload php8.x-fpm
```

Do not alter the database record until route-cache behavior has been ruled out.

## 10. Completion Report

Record these details after every deployment:

- Environment and branch
- Deployed commit hash
- Migration result
- Cache commands executed
- `.env` and runtime permission checks
- PHP-FPM and Nginx status
- Homepage and feature health-check results
- Tests that could not run and why
