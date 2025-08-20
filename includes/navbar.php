<?php
require_once __DIR__ . '/init.php';
$profile = load_json(STORAGE_PATH . '/profile.json', []);
$siteBrand = $profile['name'] ?? 'My Portfolio';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white pastel-nav shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="index.php"><i class="bi bi-gem text-primary me-2"></i><?= e($siteBrand) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="certificates.php">Certificates</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
  </nav>
  <div class="container mt-3">
    <?= render_flash() ?>
  </div>

