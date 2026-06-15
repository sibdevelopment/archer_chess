# GitHub And EC2 Deployment Workflow

This document explains how this Laravel ERP should be moved from manual file uploads to a proper GitHub and AWS EC2 workflow.

The goal is simple:

- GitHub stores and tracks the application code.
- EC2 runs the live production application.
- Developers make changes locally, push to GitHub, then pull/deploy on EC2.
- Credentials stay outside GitHub.

## 1. Current Situation

The application is already hosted on an AWS EC2 machine. Until now, files were uploaded manually through terminal/SFTP style deployment.

That works for quick fixes, but it creates long-term problems:

- No clean history of who changed what.
- Hard to rollback after a bad change.
- EC2 files can drift away from the local project.
- Multiple developers can overwrite each other.
- Credentials can accidentally get copied into code and pushed.

The project should now be treated as a Git-tracked Laravel application.

## 2. Main Blocker Before Pushing To GitHub

The codebase currently contains hardcoded or exposed credential-related values. A private GitHub repository is safer than a public one, but it is still not a safe place for secrets.

Current known risks from the local audit:

| Area | Current Risk | Required Action |
|---|---|---|
| `.env.example` | Had real-looking remote DB details | Replaced with placeholders |
| Razorpay | Credentials exist in code | Move to `.env` and `config/services.php` |
| HDFC | Credentials exist in controller/command code | Move to `.env` and `config/services.php` |
| Fast2SMS | Authorization value exists in helper code | Move to `.env` |
| WhatsApp | Access token exists in helper code | Move to `.env` |
| Basic API auth | Token is hardcoded in middleware | Move to `.env` |
| Zoom common credentials | Some controller-level credentials exist | Move to `.env` if still used |
| Coach Zoom credentials | Stored in DB fields | Protect DB dumps and consider encryption |
| Plain passwords | `decrypt_password` fields exist for employees/coaches | Remove or encrypt in future cleanup |

Reference document: `docs/security/CREDENTIAL_AUDIT_REPORT.md`.

## 3. Final Rule

Do not push the project to GitHub until these are true:

- `.env` is ignored.
- `.env.example` contains only dummy placeholders.
- Hardcoded payment/API/SMS/WhatsApp credentials are moved to `.env`.
- Real credentials that were exposed in code or examples are rotated.
- DB dumps, logs, backups, keys, and uploaded files are ignored.
- `git diff --cached` has been reviewed before the first commit.

Private repo is acceptable only after sanitization. Private repo does not remove the need to rotate already exposed credentials.

## 4. Recommended Repository Model

Use this structure:

```text
GitHub private repository
    main branch       Production-ready code
    develop branch    Optional testing/staging branch
    feature branches  Developer changes

AWS EC2 server
    Pulls from main
    Keeps production .env locally
    Keeps uploaded files/storage locally
    Runs composer/npm/artisan deployment commands
```

Recommended branch policy:

- `main`: only stable production code.
- `develop`: optional branch for testing larger changes.
- `feature/<name>`: one feature or fix per branch.
- `hotfix/<name>`: urgent production fix.

## 5. What GitHub Should Contain

GitHub should contain:

```text
app/
bootstrap/
config/
database/
docs/
lang/
public/
resources/
routes/
tests/
.editorconfig
.env.example
.gitattributes
.gitignore
artisan
composer.json
composer.lock
package.json
package-lock.json
phpunit.xml
postcss.config.js
tailwind.config.js
vite.config.js
README.md
```

GitHub should not contain:

```text
.env
.env.production
.env.backup
vendor/
node_modules/
public/build/
public/hot
public/storage/
storage/app/public/
storage/logs/
storage/framework/cache/
storage/framework/sessions/
storage/framework/views/
database dumps
server backups
zip/tar files
private keys
SSL certificates
error_log
```

## 6. Environment And Secret Management

Production secrets should live in the EC2 `.env` file or in a dedicated secret manager.

Minimum approach:

```text
/var/www/archerkids/.env
```

Better future approach:

- AWS Systems Manager Parameter Store, or
- AWS Secrets Manager, or
- a deployment tool that injects environment variables.

For this project, the next practical step is to move hardcoded credentials into `.env` and read them through Laravel config.

Example `.env` keys:

```env
RAZORPAY_KEY=
RAZORPAY_SECRET=

HDFC_BASE_URL=
HDFC_API_KEY=
HDFC_API_SECRET=
HDFC_MERCHANT_ID=
HDFC_PAYMENT_PAGE_CLIENT_ID=

FAST2SMS_AUTHORIZATION=

WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_API_URL=https://graph.facebook.com/v21.0

API_BASIC_AUTH_TOKEN=

ZOOM_CLIENT_KEY=
ZOOM_CLIENT_SECRET=
ZOOM_ACCOUNT_ID=
```

Recommended Laravel pattern:

```php
// config/services.php
'razorpay' => [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
],
```

Then application code should use:

```php
config('services.razorpay.key')
config('services.razorpay.secret')
```

Avoid calling `env()` directly in controllers, helpers, commands, and views. In Laravel, `env()` should normally be used in config files.

## 7. Database-Stored Credentials

Some credentials are not in `.env`; they are stored in database records.

Examples:

```text
coachs.zoom_api_key
coachs.zoom_client_secret
coachs.zoom_id
coachs.zoom_user_id
coachs.portal_password
coachs.decrypt_password
employees.decrypt_password
students.portal_password
```

This means:

- GitHub will not expose them unless DB dumps are committed.
- Database exports must never be pushed.
- Screenshots and exports should avoid showing these fields.
- Long-term, sensitive DB fields should be encrypted or removed.

Recommended future cleanup:

1. Remove plain password storage from `decrypt_password` fields.
2. Use Laravel password reset flows instead of storing readable passwords.
3. Use encrypted casts for secrets that must remain in DB.
4. Mask secret fields in admin views.
5. Add an audit log for who viewed or changed integration credentials.

## 8. First-Time Local Git Setup

Run this only after the credential cleanup is done.

```bash
git init
git status
git add .gitignore .env.example
git add app bootstrap config database docs lang public resources routes tests
git add artisan composer.json composer.lock package.json package-lock.json phpunit.xml postcss.config.js tailwind.config.js vite.config.js README.md
git diff --cached
git commit -m "Initial sanitized project baseline"
git branch -M main
git remote add origin git@github.com:<owner>/<repo>.git
git push -u origin main
```

Before pushing, inspect staged files:

```bash
git status --short
git diff --cached --name-only
```

If `.env`, DB backups, logs, uploaded files, or zip files appear in the staged list, stop and fix `.gitignore`.

For this project, `storage/app/public` already contains uploaded media files such as blog images. Those are production/user content, not source code. Keep them on EC2 or object storage, but do not commit them to GitHub.

## 9. First-Time EC2 Migration To Git

The first EC2 migration should be done carefully because the server already has a working manually uploaded app.

Recommended safe flow:

```bash
cd /var/www
sudo cp -a archerkids archerkids-backup-$(date +%F)
```

Clone the GitHub repository:

```bash
git clone git@github.com:<owner>/<repo>.git archerkids-git
```

Copy production-only files from the old app:

```bash
cp archerkids/.env archerkids-git/.env
```

Then install dependencies and prepare Laravel:

```bash
cd /var/www/archerkids-git
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Important:

- Do not run `php artisan key:generate` on production if the existing `.env` already has `APP_KEY`.
- Changing `APP_KEY` can break encrypted data, sessions, and password reset data.
- Run `php artisan migrate --force` only after taking a DB backup.

After validation, point Nginx/Apache to the Git-based app path, or rename folders during a maintenance window:

```bash
sudo mv archerkids archerkids-manual-old
sudo mv archerkids-git archerkids
```

Then fix permissions:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Service names may differ by server:

```bash
sudo systemctl reload nginx
sudo systemctl reload php8.2-fpm
```

If Apache is used:

```bash
sudo systemctl reload apache2
```

## 10. Future EC2 Deployment Flow

After the first setup, future deployments should be simple.

```bash
cd /var/www/archerkids
git fetch origin
git status
git pull --ff-only origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

If the server uses Apache instead of Nginx:

```bash
sudo systemctl reload apache2
```

If Node is not available on production, build assets locally or in CI and deploy `public/build` intentionally. For the current simple Git pull model, installing Node on EC2 is the easier path.

## 11. SSH Access From EC2 To GitHub

Use a deploy key or a machine SSH key.

Recommended:

- Generate SSH key on EC2.
- Add the public key to GitHub as a read-only deploy key.
- Keep write access limited to developers.

Example:

```bash
ssh-keygen -t ed25519 -C "archerkids-ec2-deploy"
cat ~/.ssh/id_ed25519.pub
```

Add the public key in GitHub:

```text
Repository -> Settings -> Deploy keys -> Add deploy key
```

Do not enable write access for the deploy key unless the server truly needs to push.

## 12. Cron After Git Migration

The Laravel scheduler should point to the Git-based production path.

Recommended cron:

```cron
* * * * * cd /var/www/archerkids && php artisan schedule:run >> /dev/null 2>&1
```

After changing the project directory on EC2, verify cron is not still pointing to the old manual-upload folder.

Also verify that scheduled commands depending on Zoom, payment, or mail credentials have the required `.env` and DB values.

Reference document: `docs/deployment/CRON_ANALYSIS.md`.

## 13. Backup And Rollback Plan

Before each production deployment:

```bash
mysqldump -u <db_user> -p <db_name> > backup-$(date +%F-%H%M).sql
```

Do not store DB backups inside the Git repository.

Tag stable releases:

```bash
git tag -a v2026-05-19 -m "Production release 2026-05-19"
git push origin v2026-05-19
```

Rollback code:

```bash
cd /var/www/archerkids
git fetch --tags
git checkout <previous-tag>
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

Database rollback is more sensitive. Prefer restoring a DB backup if a migration caused production issues.

## 14. Developer Working Process

Normal feature flow:

```bash
git checkout main
git pull origin main
git checkout -b feature/<short-feature-name>
```

Make changes locally, then:

```bash
php artisan test
npm run build
git status
git diff
git add <changed-files>
git commit -m "Describe the change"
git push origin feature/<short-feature-name>
```

Then open a pull request into `main`.

After approval:

- Merge PR into `main`.
- Pull `main` on EC2.
- Run deployment commands.

## 15. Emergency Fix Rule

Avoid editing application code directly on EC2.

If an emergency production edit is unavoidable:

1. Record exactly which file was changed.
2. Copy that change back into the local repo immediately.
3. Commit and push it.
4. Pull the same commit back on EC2.

Otherwise the next `git pull` can overwrite the emergency edit or create conflicts.

## 16. Pre-Push Security Checklist

Before the first GitHub push and before every major release:

```bash
git status --short
git diff --cached --name-only
```

Search for common secret patterns:

```bash
rg --hidden -n -g "!vendor" -g "!node_modules" -g "!storage/logs" -g "!public/build" -g "!public/storage" "APP_KEY|DB_PASSWORD|SECRET|TOKEN|PASSWORD|PRIVATE KEY|RAZORPAY|HDFC|ZOOM|WHATSAPP|FAST2SMS" .
```

Recommended tools:

- Gitleaks
- TruffleHog
- GitHub secret scanning

If a real credential was committed, deleting it in a later commit is not enough. Rotate the credential because Git history still contains it.

## 17. Recommended Cleanup Sequence For This Project

Use this sequence before pushing the first production-ready baseline:

1. Sanitize `.env.example`.
2. Confirm `.env` is ignored.
3. Move Razorpay credentials to `.env`.
4. Move HDFC credentials to `.env`.
5. Move Fast2SMS and WhatsApp credentials to `.env`.
6. Move BasicAuth token to `.env`.
7. Review Zoom controller-level credentials.
8. Rotate credentials that were exposed in code or `.env.example`.
9. Confirm no DB dump, backup, log, or uploaded file is staged.
10. Commit the clean baseline.
11. Push to a private GitHub repo.
12. Configure EC2 deploy key.
13. Migrate EC2 from manual files to Git clone.

## 18. Final Recommended Future Setup

Best practical setup for this ERP:

```text
Developer machine
    -> edit code
    -> run tests/build
    -> commit
    -> push branch
    -> pull request

GitHub private repo
    -> review changes
    -> merge into main

AWS EC2
    -> git pull main
    -> composer install
    -> npm build
    -> artisan migrate/cache
    -> reload services
```

This gives the client and future developers:

- clean code history,
- controlled deployments,
- rollback ability,
- safer credential handling,
- easier handover,
- less production drift.

The most important point: GitHub should track the application, not the production secrets.
