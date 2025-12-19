<?php
declare(strict_types=1);

/**
 * Availability config (simple, editable).
 * - Default timezone: America/Los_Angeles (change if needed)
 * - Slot minutes: 30
 * - Weekly availability: Mon-Fri 09:00-17:00
 * - Manual blackout dates: add YYYY-MM-DD strings
 *
 * Special dates and blackouts can ALSO be managed in DB tables:
 * - calendar_blackouts
 * - calendar_special_dates
 */

return [
    'timezone' => getenv('APP_TIMEZONE') ?: 'America/Los_Angeles',
    'slot_minutes' => (int)(getenv('SLOT_MINUTES') ?: 30),

    // Weekly availability (0=Sun ... 6=Sat). Multiple ranges allowed per day.
    'weekly_hours' => [
        1 => [['09:00', '17:00']], // Monday
        2 => [['09:00', '17:00']], // Tuesday
        3 => [['09:00', '17:00']], // Wednesday
        4 => [['09:00', '17:00']], // Thursday
        5 => [['09:00', '17:00']], // Friday
        // 6 => [['10:00','14:00']], // Saturday (example)
        // 0 => [], // Sunday
    ],

    // Manual blackout dates (YYYY-MM-DD)
    'manual_blackouts' => [
        // '2025-12-24',
        // '2025-12-25',
    ],

    // Holiday countries to auto-blackout (seeded in DB too). Keep on for safety.
    'holiday_countries' => ['US', 'PE'],

    // Contact email addresses
    'admin_inbox' => 'contact@ongoingteam.com',
    'from_email'  => 'info@ongoingteam.com',
    'from_name'   => 'Ongoingteam',
    'reply_to'    => 'contact@ongoingteam.com',
];
