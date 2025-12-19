<?php
declare(strict_types=1);

function fetch_services_grouped(PDO $pdo): array {
    $cats = $pdo->query("SELECT id, name FROM service_categories WHERE is_active=1 ORDER BY sort_order, name")->fetchAll();
    $catMap = [];
    foreach ($cats as $c) $catMap[(int)$c['id']] = ['id'=>(int)$c['id'], 'name'=>$c['name'], 'services'=>[]];

    $stmt = $pdo->query("SELECT id, category_id, name FROM services WHERE is_active=1 ORDER BY sort_order, name");
    while ($row = $stmt->fetch()) {
        $cid = (int)$row['category_id'];
        if (!isset($catMap[$cid])) continue;
        $catMap[$cid]['services'][] = ['id'=>(int)$row['id'], 'name'=>$row['name']];
    }
    return array_values($catMap);
}
