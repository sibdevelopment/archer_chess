# Credential Audit Report

## Summary

This report separates credentials into three categories:

1. **Present but hardcoded**: Credentials exist in the code, so they are not missing, but they should be moved to `.env`.
2. **Missing from `.env`**: Code expects these values from `.env`, but they are not currently present.
3. **Dummy or placeholder values**: Values exist in `.env`, but they are local, empty, or placeholder values.

## 1. Present But Hardcoded

These credentials are available in the codebase. They are not missing, but they should be moved to `.env` for security and maintainability.

| Service | Current Location | Status | Recommendation |
|---|---|---|---|
| Razorpay | `routes/backend.php` | Present, hardcoded | Move to `.env` |
| Razorpay | `app/Console/Commands/CheckPayment.php` | Present, hardcoded | Move to `.env` |
| HDFC SmartGateway | `app/Http/Controllers/HdfcPaymentController.php` | Present, hardcoded | Move to `.env` |
| HDFC UAT/Test | `app/Console/Commands/CheckHdfcOrderStatus.php` | Present, hardcoded | Move to `.env` |
| Fast2SMS | `app/helpers.php` | Present, hardcoded | Move to `.env` |
| WhatsApp Access Token | `app/helpers.php` | Present, hardcoded | Move to `.env` |
| API Basic Auth Token | `app/Http/Middleware/BasicAuth.php` | Present, hardcoded | Move to `.env` |
| Zoom Account Credentials | `app/Http/Controllers/DataController.php` | Present, hardcoded | Move to `.env` if this feature is used |

## 2. Missing From `.env`

These values are expected from `.env`, but are not currently present.

| Credential | Where It Is Used | Status |
|---|---|---|
| `WHATSAPP_PHONE_NUMBER_ID` | `app/helpers.php`, inside `sendWhatsAppMessage()` | Missing |
| `WHATSAPP_API_URL` | `app/helpers.php`, inside `sendWhatsAppMessage()` | Missing, but code has a default fallback |
| `WHATSAPP_ACCESS_TOKEN` | Intended in `app/helpers.php`, currently commented out | Missing from `.env`; hardcoded token is used instead |
| `MAILGUN_DOMAIN` | `config/services.php` | Missing, only needed if Mailgun is used |
| `MAILGUN_SECRET` | `config/services.php` | Missing, only needed if Mailgun is used |
| `POSTMARK_TOKEN` | `config/services.php` | Missing, only needed if Postmark is used |
| `AWS_URL` | `config/filesystems.php` | Missing, only needed if S3/AWS is used |
| `AWS_ENDPOINT` | `config/filesystems.php` | Missing, only needed if a custom S3 endpoint is used |
| `DB_HOST_SECOND` | `config/database.php`, second MySQL connection | Missing, only needed if second DB connection is used |
| `DB_DATABASE_SECOND` | `config/database.php`, second MySQL connection | Missing, only needed if second DB connection is used |
| `DB_USERNAME_SECOND` | `config/database.php`, second MySQL connection | Missing, only needed if second DB connection is used |
| `DB_PASSWORD_SECOND` | `config/database.php`, second MySQL connection | Missing, only needed if second DB connection is used |

## 3. Dummy Or Placeholder Values In `.env`

These values exist in `.env`, but they are local, empty, or placeholder values rather than production credentials.

| Env Key | Current Type | Notes |
|---|---|---|
| `MAIL_MAILER=log` | Local/dev value | Emails are logged, not sent through SMTP |
| `MAIL_HOST=127.0.0.1` | Local placeholder | Local mail host |
| `MAIL_PORT=1025` | Local placeholder | Mailpit/local testing port |
| `MAIL_USERNAME=null` | Placeholder | No SMTP username configured |
| `MAIL_PASSWORD=null` | Placeholder | No SMTP password configured |
| `MAIL_ENCRYPTION=null` | Placeholder | No SMTP encryption configured |
| `MAIL_FROM_ADDRESS=hello@example.com` | Placeholder | Dummy sender email |
| `MAIL_FROM_NAME="${APP_NAME}"` | Default placeholder | Uses app name |
| `AWS_ACCESS_KEY_ID=` | Empty placeholder | Only needed if S3/AWS is used |
| `AWS_SECRET_ACCESS_KEY=` | Empty placeholder | Only needed if S3/AWS is used |
| `AWS_BUCKET=` | Empty placeholder | Only needed if S3/AWS is used |
| `PUSHER_APP_ID=` | Empty placeholder | Only needed if Pusher broadcasting is used |
| `PUSHER_APP_KEY=` | Empty placeholder | Only needed if Pusher broadcasting is used |
| `PUSHER_APP_SECRET=` | Empty placeholder | Only needed if Pusher broadcasting is used |
| `PUSHER_HOST=` | Empty placeholder | Only needed if Pusher broadcasting is used |
| `DB_HOST=127.0.0.1` | Local value | Local database host |
| `DB_USERNAME=root` | Local value | Local XAMPP database user |
| `DB_PASSWORD=` | Local value | Empty local database password |
| `REDIS_PASSWORD=null` | Placeholder | Only needed if Redis password is used |

## 4. Credentials Stored In Database

These are not expected from `.env` in the current code. They are stored per coach in the database.

| Credential Fields | Table | Used For |
|---|---|---|
| `zoom_api_key` | `coachs` | Zoom OAuth and meeting creation |
| `zoom_client_secret` | `coachs` | Zoom OAuth and meeting creation |
| `zoom_id` | `coachs` | Zoom account ID |
| `zoom_user_id` | `coachs` | Zoom user-specific meeting and recording APIs |

These database credentials are required for:

- Batch meeting creation
- Demo session meeting creation
- Masterclass meeting creation
- Cover-up class meeting creation
- Zoom recording fetch cron jobs

## 5. Cron Credential Status

The Laravel schedule is defined in `app/Console/Kernel.php`.

| Cron Command | Credential Status |
|---|---|
| `get:coverupclass-recording` | Requires Zoom credentials from the `coachs` table |
| `get:masterclass-recording` | Requires Zoom credentials from the `coachs` table |
| `get:meeting-recordings` | Requires Zoom credentials from the `coachs` table |
| `get:demo-recordings` | Requires Zoom credentials from the `coachs` table |
| `cancel:delay-batch` | Requires DB only |
| `masterclass:reminder` | Mail is configured as `log`, so real SMTP is not active |
| `tournament:reminder` | Mail is configured as `log`, so real SMTP is not active |
| `check:payment` | Razorpay credentials are present but hardcoded |
| `check:fess-due-students` | Mail is configured as `log`, so real SMTP is not active |
| `set:fess-due-in-usa-canada` | Mail is configured as `log`, so real SMTP is not active |
| `set:fess-due-in-uk` | Mail is configured as `log`, so real SMTP is not active |

## 6. Recommended `.env` Additions

These keys should be added when moving hardcoded credentials into `.env`.

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

## 7. Security Concern

`.env.example` contains real-looking remote database credentials. This file should contain safe placeholder values only.

Recommended actions:

1. Replace real-looking values in `.env.example` with dummy placeholders.
2. Rotate any credentials that were exposed in code or `.env.example`.
3. Move hardcoded integration credentials to `.env`.
4. Read integration credentials through `config/services.php` instead of directly from controllers, commands, or helpers.

## 8. Final Notes

- Razorpay, HDFC, Fast2SMS, WhatsApp token, API token, and one Zoom credential set are not missing because they exist in code, but they are hardcoded and should be moved to `.env`.
- SMTP values are present in `.env`, but they are dummy/local placeholders.
- `WHATSAPP_PHONE_NUMBER_ID` is genuinely missing because the code reads it from `.env`.
- Zoom coach credentials are expected from the database, not `.env`.
