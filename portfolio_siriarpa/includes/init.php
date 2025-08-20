<?php
// Initialization, helpers, and storage/upload setup

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Paths
define('BASE_PATH', dirname(__DIR__));
define('STORAGE_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'storage');
define('UPLOADS_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'uploads');
define('CERT_UPLOADS_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'certificates');
define('PROFILE_UPLOADS_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'profile');

// Ensure directories exist
@mkdir(STORAGE_PATH, 0775, true);
@mkdir(UPLOADS_PATH, 0775, true);
@mkdir(CERT_UPLOADS_PATH, 0775, true);
@mkdir(PROFILE_UPLOADS_PATH, 0775, true);

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
    } else {
        $_SESSION['csrf_token'] = md5(uniqid((string)mt_rand(), true));
    }
}

function csrf_field(): string {
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf(): bool {
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postedToken = $_POST['csrf_token'] ?? '';
    return is_string($postedToken) && $postedToken !== '' && hash_equals($sessionToken, $postedToken);
}

// Helpers
function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function sanitize_input(?string $value): string {
    $value = (string)$value;
    $value = trim($value);
    return strip_tags($value);
}

function load_json($path, $default = []) {
    if (!is_string($path) || $path === '' || !file_exists($path)) {
        return $default;
    }
    $raw = @file_get_contents($path);
    if ($raw === false || $raw === '') {
        return $default;
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return $default;
    }

    // Merge defaults with data safely
    $merged = $default;
    foreach ($default as $key => $value) {
        if (!isset($data[$key])) continue;
        if (is_array($value) && !is_array($data[$key])) continue;
        $merged[$key] = $data[$key];
    }

    // Ensure 'skills' is always an array
    if (!isset($merged['skills']) || !is_array($merged['skills'])) {
        $merged['skills'] = [];
    }

    return $merged;
}

function save_json(string $filePath, $data): bool {
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($filePath, $json, LOCK_EX) !== false;
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = $_SESSION['flash'] ?? [];
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function render_flash(): string {
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $html = '';
    foreach ($_SESSION['flash'] as $item) {
        $type = $item['type'] ?? 'info';
        $message = $item['message'] ?? '';
        $bootstrapType = in_array($type, ['success','danger','warning','info','primary','secondary','light','dark'], true) ? $type : 'info';
        $html .= '<div class="alert alert-' . e($bootstrapType) . ' alert-dismissible fade show soft-shadow" role="alert">'
            . e($message)
            . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    unset($_SESSION['flash']);
    return $html;
}

function save_uploaded_image(array $file, string $destinationDir): ?string {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    $mime = null;
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = @$finfo->file($file['tmp_name']);
    }
    if (!$mime && function_exists('mime_content_type')) {
        $mime = @mime_content_type($file['tmp_name']);
    }

    $ext = null;
    if ($mime && isset($allowed[$mime])) {
        $ext = $allowed[$mime];
    } else {
        $nameExt = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (in_array($nameExt, ['jpg','jpeg','png','webp'], true)) {
            $ext = $nameExt === 'jpeg' ? 'jpg' : $nameExt;
        } else {
            return null;
        }
    }

    if (!is_dir($destinationDir)) {
        @mkdir($destinationDir, 0775, true);
    }
    $newName = uniqid('img_', true) . '.' . $ext;
    $destPath = rtrim($destinationDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newName;
    if (move_uploaded_file($file['tmp_name'], $destPath)) {
        return $newName;
    }
    return null;
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}
