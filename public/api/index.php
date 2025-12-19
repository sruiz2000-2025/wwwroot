<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$path = rtrim($path, '/');

switch ($path) {
  case '/api/services':
    require __DIR__ . '/services.php';
    break;
  case '/api/availability':
    require __DIR__ . '/availability.php';
    break;
  case '/api/book':
    require __DIR__ . '/book.php';
    break;
  default:
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok'=>false,'error'=>'Not found']);
}
