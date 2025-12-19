<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/contact_config.php';
require_once __DIR__ . '/../../lib/helpers.php';
require_once __DIR__ . '/../../lib/availability.php';

$cfg = require __DIR__ . '/../../config/contact_config.php';

$start = clean_str($_GET['start'] ?? '');
$days = (int)($_GET['days'] ?? 7);
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
    json_response(['ok'=>false, 'error'=>'Invalid start date'], 400);
}
$days = max(1, min(21, $days));

$tz = new DateTimeZone($cfg['timezone']);
$startDt = new DateTimeImmutable($start . ' 00:00:00', $tz);
$endDt = $startDt->modify("+{$days} days");

// Fallback: if DB is not available, return a simple Mon–Fri schedule (local time)
if (!($pdo instanceof PDO)) {
    $slots = [];
    for ($i=0; $i<$days; $i++) {
        $day = $startDt->modify("+{$i} days");
        $dow = (int)$day->format('N'); // 1=Mon ... 7=Sun
        if ($dow >= 1 && $dow <= 5) {
            foreach (['09:00','11:00','13:00','15:00'] as $t) {
                $slots[] = [
                    'start_local' => $day->format('Y-m-d') . " " . $t . ":00",
                    'timezone' => $cfg['timezone'],
                    'status' => 'open'
                ];
            }
        }
    }
    json_response(['ok'=>true,'fallback'=>true,'slots'=>$slots]);
}

try {
    $blackouts = load_blackouts($pdo, $cfg);
    $specialDates = load_special_dates($pdo);

    // Load appointments in range (UTC) using wider window to be safe
    $booked = load_existing_appointments($pdo, $startDt, $endDt);

    $out = [];
    for ($i=0; $i<$days; $i++){
        $d = $startDt->modify("+{$i} days")->format('Y-m-d');
        $slots = build_slots_for_day($d, $cfg, $blackouts, $specialDates, $booked);
        $out[] = ['date'=>$d, 'slots'=>$slots];
    }

    header('Cache-Control: no-store');
    json_response(['ok'=>true, 'timezone'=>$cfg['timezone'], 'days'=>$out]);
} catch (Throwable $e) {
    json_response(['ok'=>false, 'error'=>'Failed to compute availability'], 500);
}
