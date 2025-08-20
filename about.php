<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'About · Pastel Portfolio';
$profile = load_json(STORAGE_PATH . '/profile.json', []);
?>
<?php include __DIR__ . '/includes/head.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card soft-card h-100">
        <div class="card-body text-center">
          <img src="<?= e($profile['avatar'] ?? '../portfolio_siriarpa/picture/me.jpg') ?>" alt="Avatar" class="avatar mb-3">
          <div class="h5 mb-1"><?= e($profile['name'] ?? 'Siriarpa') ?></div>
          <div class="text-muted mb-3"><?= e($profile['title'] ?? 'Information Technology student') ?></div>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <?php foreach (($profile['skills'] ?? []) as $skill): ?>
              <span class="chip"><i class="bi bi-check2 me-1"></i><?= e($skill) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card soft-card h-100">
        <div class="card-body">
          <h1 class="h4 fw-semibold mb-3">About Me</h1>
          <p><?= e($profile['bio'] ?? 'Hello! I am a Bachelor’s degree student in Information Technology with a passion for continuous learning and personal growth. Beyond my interest in technology, I love cooking and especially enjoy making desserts — with a dream of opening my own bakery in the future. In my free time, I enjoy traveling to new places, gaining fresh perspectives and experiences that inspire both my personal life and my work.') ?></p>

          <h2 class="h6 fw-semibold mt-4">Experience</h2>
          <ul class="timeline list-unstyled">
            <li class="timeline-item"><div class="ms-3"><div class="fw-semibold">I used to intern at the Phrae Provincial Statistical Office.</div><div class="text-muted small">Oct 2025 - Feb 2026</div></div></li>
            <li class="timeline-item"><div class="ms-3"><div class="fw-semibold">I used to do an internship at Ban Wiang Subdistrict Municipality.</div><div class="text-muted small">Oct 2022 - Feb 2023</div></div></li>
          </ul>

          <h2 class="h6 fw-semibold mt-4">Education</h2>
          <ul class="mb-0">
            <li> Rajamangala University of Technology Lanna Lampang </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

