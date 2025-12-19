## Purpose

This file gives AI coding agents the minimal, actionable knowledge to be productive in this PHP website repo (contact/booking, services, availability). Focus on the files and patterns below when making changes.

## Big picture

- This is a small PHP site with a recommended web root at `/public` (see `README_SETUP.md`).
- Frontend pages live in `public/` and partials in `public/partials/`.
- API router: `public/api/index.php` dispatches three JSON endpoints: `/api/services`, `/api/availability`, `/api/book`.
- Business logic lives in `lib/` (e.g., `services_repo.php`, `availability.php`, `SmtpMailer.php`, `helpers.php`).
- Configuration is in `config/`; database and mail rely on environment variables where possible (`config/db.php`, `config/mail.php`).

## Key workflows & how to run locally

- Point your web server document root to `public/` (or copy contents into your existing public folder). See `README_SETUP.md`.
- Database: import `db/schema.sql` and set `DB_NAME`, `DB_USER`, `DB_PASS` or edit `config/db.php`. `config/db.php` sets `$pdo` or `null` (fallback no-DB mode is supported).
- Email: set `SMTP_USER` and `SMTP_PASS` env vars or configure `config/mail.php`. The project uses the internal `SmtpMailer` (no external PHPMailer dependency).

## Project-specific conventions & patterns

- Fallback/no-DB mode: many endpoints check `if (!($pdo instanceof PDO))` and return reasonable defaults. Agents must preserve that fallback behavior when editing APIs.
- JSON API helper: use `json_response($data, $status)` from `lib/helpers.php` — it sets headers and `exit`.
- Input sanitization: use `clean_str()` and `validate_email()` in `lib/helpers.php` for request data.
- CSRF: `csrf_token()` / `csrf_verify()` are session-based and used by `public/api/book.php`.
- Timezones: `config/contact_config.php` provides `timezone` used across `availability.php` and `book.php`. Always treat stored appointment times as UTC in DB and convert to local time in UI code.

## Booking & availability specifics (important)

- Booking attempts are protected by a DB UNIQUE constraint on `appointments.start_datetime` (see `db/schema.sql` comment in `README_SETUP.md`). `public/api/book.php` wraps inserts in a transaction and handles unique constraint failures by returning 409 or converting to a special request.
- Availability is computed in `public/api/availability.php` using `lib/availability.php` and `config/contact_config.php` blackouts/slot rules; tests should account for `slot_minutes` and `timezone` values.

## Email behavior

- `lib/SmtpMailer.php` performs direct SMTP (AUTH LOGIN) to the host configured in `config/mail.php`. For testing, set `SMTP_HOST`/`SMTP_PORT`/`SMTP_USER`/`SMTP_PASS` environment variables or temporarily stub `SmtpMailer::send()`.

## Files to inspect when making changes

- Routing / endpoints: `public/api/index.php`, `public/api/*.php`
- DB connection and fallback: `config/db.php`
- Booking flow: `public/api/book.php` and `db/schema.sql`
- Availability logic: `public/api/availability.php`, `lib/availability.php`
- Services data: `lib/services_repo.php`, `public/api/services.php`
- Helpers & response utils: `lib/helpers.php`
- Email transport: `lib/SmtpMailer.php`, `config/mail.php`

## PR / change guidance (how to make safe edits)

- Preserve fallback/no-DB behavior unless intentionally removing it. Many integrations rely on `$pdo` being nullable.
- When changing booking logic, keep transactional `beginTransaction()`/`commit()`/`rollBack()` behavior and properly handle unique constraint errors (the code checks for `uniq_appointment_start`).
- Use `json_response()` for API output and set appropriate HTTP statuses (400/403/409/500) as the existing code does.

## Quick examples

- Add a new API route: update `public/api/index.php` to route `/api/yourroute` to `public/api/yourroute.php` and place logic there; use `require_once __DIR__ . '/../../lib/helpers.php'` and `json_response()`.
- Read DB safely: include `config/db.php` and treat `$pdo` as possibly `null`.

## When you need more context

- Look at `README_SETUP.md` for deployment and env var guidance.
- Inspect `db/schema.sql` for constraints and table structure (double-booking protection).

If any section is unclear or you'd like more examples (error handling, slot building, or email testing), tell me which area and I'll expand or adjust this file.
