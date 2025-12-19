<?php
// Shared header
// Ensure CSRF token exists for forms / AJAX
if (!isset($csrf)) {
  $candidates = [
    __DIR__ . '/../../lib/helpers.php',
    __DIR__ . '/../lib/helpers.php',
    __DIR__ . '/lib/helpers.php',
  ];
  foreach ($candidates as $f) {
    if (file_exists($f)) { require_once $f; break; }
  }
  if (function_exists('csrf_token')) {
    $csrf = csrf_token();
  } else {
    $csrf = '';
  }
}
?>
<!doctype html>
<html lang="en">
<?php include __DIR__ . '/head.php'; ?>
<body>

<div class="header">
  <div class="header-inner">
    <a class="brand" href="/">
      <img src="/img/ongoingteam-o.svg" alt="Ongoingteam" onerror="this.style.display='none'">
      <span>Ongoingteam</span>
    </a>

    <div class="nav">
      <a class="btn" href="/services">Services</a>
      <a class="btn" href="/prices">Prices</a>
      <a class="btn" href="/contact">Contact</a>
      <a class="btn primary" href="/contact#book">Book a Meeting</a>
    </div>
  </div>
</div>
