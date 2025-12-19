Ongoingteam - New Files Pack (Contact + Prices + Services + Calendar)

1) Upload folders:
- config/
- db/
- lib/
- public/

2) Point your web root to /public (recommended), or copy contents of /public into your current public folder.

3) Database:
- Create a MySQL database on your hosting.
- Edit config/db.php:
  - DB_NAME
  - DB_PASS
  OR set env vars: DB_NAME, DB_PASS (recommended)

- Import db/schema.sql

4) Email (IONOS SMTP):
- Set environment variables (recommended):
  - SMTP_USER=info@ongoingteam.com
  - SMTP_PASS=your_password_here
- Or edit config/mail.php on the server.

5) URLs:
- /         (index.php)
- /services.php
- /prices.php
- /contact.php
- APIs:
  - /api/services.php
  - /api/availability.php
  - /api/book.php

6) Double-booking protection:
- appointments.start_datetime has a UNIQUE key, so two users can't book the same slot.

7) Special date requests:
- If no slot is selected, but a specific date/time is provided, we save it as zoom_special_request and email it to the admin inbox.

Notes:
- Holiday blackouts are a starter set (US+PE fixed-date holidays). Add more in calendar_blackouts as needed.
