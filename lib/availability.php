<?php
declare(strict_types=1);

function load_blackouts(PDO $pdo, array $cfg): array {
    $dates = [];
    foreach ($cfg['manual_blackouts'] ?? [] as $d) $dates[$d] = true;

    // From DB
    $stmt = $pdo->query("SELECT date FROM calendar_blackouts");
    while ($r = $stmt->fetch()) $dates[$r['date']] = true;

    return $dates; // map date => true
}

function load_special_dates(PDO $pdo): array {
    $map = [];
    $stmt = $pdo->query("SELECT date, start_time, end_time, slot_minutes FROM calendar_special_dates");
    while ($r = $stmt->fetch()) {
        $map[$r['date']] = [
            'start' => $r['start_time'],
            'end' => $r['end_time'],
            'slot_minutes' => (int)$r['slot_minutes'],
        ];
    }
    return $map;
}

function load_existing_appointments(PDO $pdo, DateTimeImmutable $start, DateTimeImmutable $end): array {
    $stmt = $pdo->prepare("SELECT start_datetime FROM appointments WHERE status IN ('booked','pending') AND start_datetime >= ? AND start_datetime < ?");
    $stmt->execute([
        $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s')
    ]);
    $set = [];
    while ($r = $stmt->fetch()) $set[$r['start_datetime']] = true;
    return $set; // UTC datetime string => true
}

function build_slots_for_day(string $date, array $cfg, array $blackouts, array $specialDates, array $bookedUtcSet): array {
    if (isset($blackouts[$date])) return [];

    $tz = new DateTimeZone($cfg['timezone']);
    $slotMinDefault = (int)$cfg['slot_minutes'];

    $dow = (int)(new DateTimeImmutable($date, $tz))->format('w'); // 0-6
    $ranges = $cfg['weekly_hours'][$dow] ?? [];

    // Special override
    if (isset($specialDates[$date])) {
        $ranges = [[ $specialDates[$date]['start'], $specialDates[$date]['end'] ]];
        $slotMinDefault = (int)$specialDates[$date]['slot_minutes'];
    }

    $slots = [];
    foreach ($ranges as [$startStr, $endStr]) {
        $start = new DateTimeImmutable($date . ' ' . $startStr, $tz);
        $end   = new DateTimeImmutable($date . ' ' . $endStr, $tz);

        for ($t = $start; $t < $end; $t = $t->modify("+{$slotMinDefault} minutes")) {
            $utc = $t->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            if (!isset($bookedUtcSet[$utc])) {
                $slots[] = [
                    'label' => $t->format('g:i A'),
                    'value' => $t->format('Y-m-d H:i:s'),
                    'timezone' => $cfg['timezone'],
                ];
            }
        }
    }
    return $slots;
}
