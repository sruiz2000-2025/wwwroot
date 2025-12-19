<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../lib/helpers.php';
require_once __DIR__ . '/../../lib/services_repo.php';

if (!($pdo instanceof PDO)) {
    // Fallback (no database): hardcoded services
    $categories = [
      [
        'name' => 'Virtual Assistant',
        'services' => [
          ['id'=>1,'name'=>'Airbnb / Short‑Term Rental Operations'],
          ['id'=>2,'name'=>'Real Estate Admin Support'],
          ['id'=>3,'name'=>'Email & Calendar Organization'],
          ['id'=>4,'name'=>'Case Management & Follow‑Ups'],
          ['id'=>5,'name'=>'Personal Assistant Tasks'],
        ],
      ],
      [
        'name' => 'Other Services',
        'services' => [
          ['id'=>101,'name'=>'Social Media Management'],
          ['id'=>102,'name'=>'Video Editing'],
          ['id'=>103,'name'=>'Lead Intake & Client Communication'],
          ['id'=>104,'name'=>'Documentation & Data Entry'],
          ['id'=>105,'name'=>'Custom Workflow Support'],
        ],
      ],
    ];
    header('Cache-Control: public, max-age=300');
    json_response(['ok'=>true,'categories'=>$categories]);
}

try {
    $categories = fetch_services_grouped($pdo);
    // Cache-friendly headers (short)
    header('Cache-Control: public, max-age=300');
    json_response(['ok'=>true, 'categories'=>$categories]);

} catch (Throwable $e) {
    json_response(['ok'=>false, 'error'=>'Failed to load services'], 500);
}
