<?php
declare(strict_types=1);

/**
 * SMTP mail config (IONOS).
 * IMPORTANT: Put the password in an environment variable (recommended),
 * or set it directly here on your server (NOT in git).
 */
return [
    'smtp_host' => 'smtp.ionos.com',
    'smtp_port' => 465,
    'smtp_secure' => 'ssl', // 'ssl' for 465, 'tls' for 587
    'smtp_user' => getenv('SMTP_USER') ?: 'info@ongoingteam.com',
    'smtp_pass' => getenv('SMTP_PASS') ?: 'Redlands@10',
];
