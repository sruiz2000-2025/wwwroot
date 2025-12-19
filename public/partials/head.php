<?php
// shared <head>
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Ongoingteam</title>
  <meta name="description" content="Premium virtual assistant services with real outcomes.">

  <!-- CSRF for AJAX -->
  <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8'); ?>">

  <!-- Base + Luxury styles (ORDER MATTERS) -->
  <link rel="preload" href="/assets/css/lux.css" as="style">
  <link rel="stylesheet" href="/assets/css/lux.css">
</head>
