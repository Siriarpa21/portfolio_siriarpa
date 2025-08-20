<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Contact · Pastel Portfolio';

$contactPath = STORAGE_PATH . '/messages.json';
$messages = load_json($contactPath, []);
if (!is_array($messages)) $messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
  if (!verify_csrf()) {
    set_flash('danger', 'Invalid form submission.');
    redirect('contact.php');
  }
  $name = sanitize_input($_POST['name'] ?? '');
  $email = sanitize_input($_POST['email'] ?? '');
  $message = sanitize_input($_POST['message'] ?? '');
  if ($name && $email && $message) {
    $messages[] = [
      'name' => $name,
      'email' => $email,
      'message' => $message,
      'at' => date('c'),
    ];
    save_json($contactPath, $messages);
    set_flash('success', 'Thanks! Your message has been sent.');
    redirect('contact.php');
  } else {
    set_flash('warning', 'Please fill in all fields.');
    redirect('contact.php');
  }
}

?>
<?php include __DIR__ . '/includes/head.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card soft-card h-100">
        <div class="card-body">
          <h1 class="h5 fw-semibold mb-3">If you have any questions, you can contact me here.</h1>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="contact_form" value="1">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <button class="btn btn-pastel">Send</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card soft-card h-100">
        <div class="card-body">
          <h2 class="h6 fw-semibold mb-3">Follow</h2>
          <div class="d-flex gap-3">
            <a class="text-decoration-none" href="https://www.facebook.com/livetohearmusic"><i class="bi bi-facebook fs-3"></i></a>
            <a class="text-decoration-none" href="https://www.instagram.com/crippiie"><i class="bi bi-instagram fs-3"></i></a>
            <a class="text-decoration-none" href="https://line.me/ti/p/vZzrl20sBv"><i class="bi bi-line fs-3"></i></a>
          </div>

          <hr class="my-4">
          <h3 class="h6 fw-semibold">Recent messages (local)<br></h3>
          <?php if (empty($messages)): ?>
            <p class="text-muted mb-0">No messages yet.</p>
          <?php else: ?>
            <div class="list-group list-group-flush">
              <?php foreach (array_reverse($messages) as $m): ?>
                <div class="list-group-item">
                <div class="fw-semibold"><?= e($m['name'] ?? 'Anonymous') ?> <span class="text-muted small">&lt;<?= e($m['email'] ?? 'arrom.mm21@gmail.com') ?>&gt;</span></div>  
                <div class="small text-muted mb-1"><?= e(date('M j, Y H:i', strtotime($m['at'] ?? date('c')))) ?></div>
                <div><?= e($m['message'] ?? '') ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

