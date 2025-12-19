<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/contact_config.php';
require_once __DIR__ . '/../../config/mail.php';
require_once __DIR__ . '/../../lib/helpers.php';
require_once __DIR__ . '/../../lib/SmtpMailer.php';

$cfg = require __DIR__ . '/../../config/contact_config.php';
$mailCfg = require __DIR__ . '/../../config/mail.php';

$noDb = !($pdo instanceof PDO);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok'=>false, 'error'=>'Method not allowed'], 405);
}

$payload = json_decode(file_get_contents('php://input') ?: '[]', true) ?: [];

$csrfToken = $payload['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
if (!csrf_verify($csrfToken)) {
    json_response(['ok'=>false, 'error'=>'Invalid session'], 403);
}

$fullName = clean_str($payload['full_name'] ?? '', 160);
$email = clean_str($payload['email'] ?? '', 190);
$phone = clean_str($payload['phone'] ?? '', 40);
$lang = clean_str($payload['language_preference'] ?? 'English', 30);
$contactType = clean_str($payload['contact_type'] ?? '', 40);
$details = clean_str($payload['details'] ?? '', 5000);
$serviceIdsCsv = clean_str($payload['service_ids'] ?? '', 2000);

$tzName = $cfg['timezone'];
$tz = new DateTimeZone($tzName);

// Zoom fields
$selectedSlotLocal = clean_str($payload['slot_local'] ?? ''); // 'Y-m-d H:i:s' local
$preferredDate = clean_str($payload['preferred_date'] ?? ''); // Y-m-d
$preferredTime = clean_str($payload['preferred_time'] ?? ''); // HH:MM

if ($fullName === '' || !validate_email($email)) {
    json_response(['ok'=>false, 'error'=>'Please provide a valid name and email.'], 422);
}
if ($phone !== '' && !validate_phone($phone)) {
    json_response(['ok'=>false, 'error'=>'Please provide a valid phone number.'], 422);
}

$allowedTypes = ['zoom','phone','email','pricing_request'];
if (!in_array($contactType, $allowedTypes, true)) {
    json_response(['ok'=>false, 'error'=>'Invalid contact type'], 422);
}

// Parse service IDs
$serviceIds = [];
if ($serviceIdsCsv !== '') {
    foreach (explode(',', $serviceIdsCsv) as $id) {
        $id = trim($id);
        if ($id !== '' && ctype_digit($id)) $serviceIds[] = (int)$id;
    }
    $serviceIds = array_values(array_unique($serviceIds));
}

// Decide booking vs special request
$isSpecial = 0;
$preferredUtc = null;
$appointmentId = null;

try {
    $special_request = false;
    if ($noDb) {
        $special_request = true;
    }

    if (!$noDb) {
if (!$noDb) { $pdo->beginTransaction(); }

    if ($contactType === 'zoom') {
        // If user selected an available slot: try to book it
        if ($selectedSlotLocal !== '' && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $selectedSlotLocal)) {
            $localStart = new DateTimeImmutable($selectedSlotLocal, $tz);
            $slotMinutes = (int)$cfg['slot_minutes'];
            $localEnd = $localStart->modify("+{$slotMinutes} minutes");

            $startUtc = $localStart->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $endUtc   = $localEnd->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

            // Insert appointment (unique constraint prevents double booking)
            $stmt = $pdo->prepare("INSERT INTO appointments (start_datetime, end_datetime, timezone, status) VALUES (?, ?, ?, 'booked')");
            $stmt->execute([$startUtc, $endUtc, $tzName]);
            $appointmentId = (int)$pdo->lastInsertId();

            $preferredUtc = $startUtc;
        } else {
            // Special request: preferred date/time required
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $preferredDate) || !preg_match('/^\d{2}:\d{2}$/', $preferredTime)) {
                throw new RuntimeException('Preferred date/time required for special request.');
            }
            $local = new DateTimeImmutable($preferredDate . ' ' . $preferredTime . ':00', $tz);
            $preferredUtc = $local->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $isSpecial = 1;
            $contactType = 'zoom_special_request';
        }
    }

    // Create contact request
    $stmt = $pdo->prepare("
        INSERT INTO contact_requests
          (full_name, email, phone, language_preference, contact_type, details, preferred_datetime, timezone, is_special_request)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $fullName,
        $email,
        $phone !== '' ? $phone : null,
        $lang,
        $contactType,
        $details !== '' ? $details : null,
        $preferredUtc,
        $tzName,
        $isSpecial
    ]);
    $requestId = (int)$pdo->lastInsertId();

    if ($serviceIds) {
        $ins = $pdo->prepare("INSERT IGNORE INTO contact_request_services (contact_request_id, service_id) VALUES (?, ?)");
        foreach ($serviceIds as $sid) $ins->execute([$requestId, $sid]);
    }

    if (!$noDb) { $pdo->commit(); }
}


} catch (Throwable $e) {
    if (!$noDb && $pdo->inTransaction()) $pdo->rollBack();

    // If booking failed due to unique constraint, treat as special request using preferred date/time if provided
    $msg = $e->getMessage();
    if (strpos($msg, 'uniq_appointment_start') !== false && $contactType === 'zoom') {
        json_response(['ok'=>false, 'error'=>'That slot was just taken. Please pick another time or request a specific date/time.'], 409);
    }
    json_response(['ok'=>false, 'error'=>'Could not submit your request.'], 500);
}

// Load selected services names for email summary
$serviceNames = [];
if ($serviceIds) {
    $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));
    $stmt = $pdo->prepare("SELECT s.name, c.name AS category FROM services s JOIN service_categories c ON c.id=s.category_id WHERE s.id IN ($placeholders)");
    $stmt->execute($serviceIds);
    while ($r = $stmt->fetch()) $serviceNames[] = "{$r['name']} ({$r['category']})";
}
$servicesLine = $serviceNames ? implode(', ', $serviceNames) : 'None selected';

// Build email content
$adminTo = $cfg['admin_inbox'];
$fromEmail = $cfg['from_email'];
$fromName = $cfg['from_name'];
$replyTo = $cfg['reply_to'];

$whenLine = '';
if ($preferredUtc) {
    $localWhen = (new DateTimeImmutable($preferredUtc, new DateTimeZone('UTC')))->setTimezone($tz)->format('Y-m-d g:i A');
    $whenLine = $localWhen . " ({$tzName})";
}

$subjectAdmin = "New website request — {$fullName}";
$subjectCustomer = "We received your request — Ongoingteam";

$noReply = "This is an automated message. Please do not reply to this email. If you need to update your request, contact us at {$replyTo}.";

$adminText = "New request received\n"
    . "Name: {$fullName}\n"
    . "Email: {$email}\n"
    . "Phone: " . ($phone ?: '-') . "\n"
    . "Language: {$lang}\n"
    . "Type: {$contactType}\n"
    . ($whenLine ? "Requested/Booked: {$whenLine}\n" : "")
    . "Services: {$servicesLine}\n\n"
    . "Details:\n{$details}\n\n"
    . $noReply . "\n";

$adminHtml = "<h2>New request received</h2>"
    . "<p><strong>Name:</strong> " . htmlspecialchars($fullName) . "<br>"
    . "<strong>Email:</strong> " . htmlspecialchars($email) . "<br>"
    . "<strong>Phone:</strong> " . htmlspecialchars($phone ?: '-') . "<br>"
    . "<strong>Language:</strong> " . htmlspecialchars($lang) . "<br>"
    . "<strong>Type:</strong> " . htmlspecialchars($contactType) . "<br>"
    . ($whenLine ? "<strong>Requested/Booked:</strong> " . htmlspecialchars($whenLine) . "<br>" : "")
    . "<strong>Services:</strong> " . htmlspecialchars($servicesLine) . "</p>"
    . "<p><strong>Details:</strong><br>" . nl2br(htmlspecialchars($details)) . "</p>"
    . "<hr><p style='color:#5b667a;font-size:12px;'>" . htmlspecialchars($noReply) . "</p>";

$customerText = "Hi {$fullName},\n\n"
    . "Thanks for reaching out to Ongoingteam. We received your request and will follow up shortly.\n\n"
    . "Summary:\n"
    . "Type: {$contactType}\n"
    . ($whenLine ? "Requested/Booked: {$whenLine}\n" : "")
    . "Services: {$servicesLine}\n\n"
    . "Your details:\n{$details}\n\n"
    . $noReply . "\n";

$customerHtml = "<p>Hi " . htmlspecialchars($fullName) . ",</p>"
    . "<p>Thanks for reaching out to <strong>Ongoingteam</strong>. We received your request and will follow up shortly.</p>"
    . "<div style='border:1px solid #e6eaf1;border-radius:14px;padding:12px;background:#fbfcff;'>"
    . "<p style='margin:0 0 8px;'><strong>Summary</strong></p>"
    . "<p style='margin:0;'><strong>Type:</strong> " . htmlspecialchars($contactType) . "<br>"
    . ($whenLine ? "<strong>Requested/Booked:</strong> " . htmlspecialchars($whenLine) . "<br>" : "")
    . "<strong>Services:</strong> " . htmlspecialchars($servicesLine) . "</p>"
    . "</div>"
    . "<p><strong>Your details:</strong><br>" . nl2br(htmlspecialchars($details)) . "</p>"
    . "<hr><p style='color:#5b667a;font-size:12px;'>" . htmlspecialchars($noReply) . "</p>";

$mailer = new SmtpMailer(
    $mailCfg['smtp_host'],
    (int)$mailCfg['smtp_port'],
    (string)$mailCfg['smtp_secure'],
    (string)$mailCfg['smtp_user'],
    (string)$mailCfg['smtp_pass']
);

// Send admin + customeri
$adminOk = false;
$custOk = false;

try {
    $adminOk = $mailer->send([
        'to' => $adminTo,
        'subject' => $subjectAdmin,
        'html' => $adminHtml,
        'text' => $adminText,
        'from_email' => $fromEmail,
        'from_name' => $fromName,
        'reply_to' => $replyTo,
    ]);
} catch (Throwable $e) { $adminOk = false; }

try {
    $custOk = $mailer->send([
        'to' => $email,
        'subject' => $subjectCustomer,
        'html' => $customerHtml,
        'text' => $customerText,
        'from_email' => $fromEmail,
        'from_name' => $fromName,
        'reply_to' => $replyTo,
    ]);
} catch (Throwable $e) { $custOk = false; }

json_response([
    'ok' => true,
    'request_id' => $requestId ?? null,
    'appointment_id' => $appointmentId,
    'email_admin_sent' => $adminOk,
    'email_customer_sent' => $custOk,
    'special_request' => (bool)$isSpecial,
]);
