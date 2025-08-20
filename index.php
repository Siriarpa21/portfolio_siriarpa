<?php
require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Home · Pastel Portfolio';
$profilePath = STORAGE_PATH . '/profile.json';

$profile = load_json($profilePath, [
    'name' => 'Siriarpa',
    'title' => 'Information Technology student',
    'bio' => 'I build delightful web experiences with PHP, JavaScript, and Bootstrap.',
    'skills' => ['PHP', 'JavaScript', 'Bootstrap', 'MySQL'],
    'avatar' => null,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_form'])) {
    if (!verify_csrf()) {
        set_flash('danger', 'Invalid form submission.');
        redirect('index.php');
    }

    $profile['name'] = sanitize_input($_POST['name'] ?? '');
    $profile['title'] = sanitize_input($_POST['title'] ?? '');
    $profile['bio'] = sanitize_input($_POST['bio'] ?? '');

    $skillsRaw = sanitize_input($_POST['skills'] ?? '');
    $profile['skills'] = array_values(array_filter(array_map('trim', explode(',', $skillsRaw))));

    if (!empty($_FILES['avatar']['name'])) {
        $saved = save_uploaded_image($_FILES['avatar'], PROFILE_UPLOADS_PATH);
        if ($saved) {
            $profile['avatar'] = 'uploads/profile/me.jpg' . $saved;
        } else {
            set_flash('warning', 'Avatar upload failed or invalid format.');
        }
    }

    save_json($profilePath, $profile);
    set_flash('success', 'Profile updated!');
    redirect('index.php');
}
?>
<?php include __DIR__ . '/includes/head.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container my-4">
    <section class="p-4 p-md-5 hero soft-shadow">
        <div class="row align-items-start g-4">
            <div class="col-md-3 text-center text-md-start">
                <img src="<?= e($profile['avatar'] ?? '../portfolio_siriarpa/picture/me.jpg') ?>" alt="Avatar" class="avatar">
            </div>
            <div class="col-md-9">
                <h1 class="h2 fw-bold mb-1"><?= e($profile['name'] ?? 'Siriarpa T.') ?></h1>
                <p class="lead mb-2"><?= e($profile['title'] ?? 'Information Technology student') ?></p>
                <p class="mb-3"><?= e($profile['bio'] ?? 'Hello, my name is [ Siriarpa ] but you can call me [ Aomsin ]. I was born on Saturday, June 21, 2003, and I come from Phrae Province, but I am currently studying in Lampang Province. I am passionate about cooking and baking, always looking for new recipes to try. I enjoy learning new things and exploring topics that interest me with great enthusiasm. In my free time, I love traveling and discovering new places. I consider myself a friendly, curious, and motivated person, eager to meet new people and gain new experiences.') ?></p>
                <div class="d-flex flex-wrap gap-2">
                <h3 class="fw-bold">Languages and programs being studied<br></h3>
                  <?php foreach ($profile['skills'] ?? ['PHP', 'JavaScript', 'Bootstrap', 'MySQL', 'Cursor', 'GitHub', 'HTML5', 'CSS', 'Vercel'] as $skill): ?>
                    <?php
                    // กำหนด icon ตาม skill (ตัวอย่าง)
                    $icon = 'bi-star-fill'; // default icon
                    switch (strtolower($skill)) {
                        case 'php': $icon = 'bi-code-slash'; break;
                        case 'javascript': $icon = 'bi-file-code'; break;
                        case 'bootstrap': $icon = 'bi-bootstrap'; break;
                        case 'mysql': $icon = 'bi-database'; break;
                        case 'cursor': $icon = 'bi-cursor'; break;
                        case 'github': $icon = 'bi-github'; break;
                        case 'html5': $icon = 'bi-html5'; break;
                        case 'css': $icon = 'bi-css'; break;
                        case 'vercel': $icon = 'bi-vercel'; break;
                    }
                    ?>
                    <span class="chip d-flex align-items-center gap-1">
                      <i class="bi <?= $icon ?>"></i> <?= e($skill) ?>
                    </span>
                  <?php endforeach; ?><br>
                   <!-- Resume Button -->
                    <div class="mt-2">
                      <a href="../portfolio_siriarpa/picture/เรซูเม่.png" download class="btn btn-pastel">
                        <i class="bi bi-download me-2"></i>Download Resume
                      </a>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </section>

    <!-- Rest of your sections remain the same -->
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
