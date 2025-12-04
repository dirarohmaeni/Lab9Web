<?php
// index.php (smart include) - robust header/footer handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();

define('ROOT_PATH', __DIR__);
define('VIEWS_PATH', ROOT_PATH . '/views');

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// pages that don't require authentication
$public_pages = ['login'];

// if user not logged in and requesting protected page -> redirect to login
if (empty($_SESSION['user_id']) && !in_array($page, $public_pages, true)) {
    header('Location: index.php?page=login');
    exit;
}

// ---------- intelligent header include (search common locations) ----------
$headerIncluded = false;
$header_candidates = [
    ROOT_PATH . '/header.php',
    ROOT_PATH . '/views/header.php',
    ROOT_PATH . '/includes/header.php',
    ROOT_PATH . '/partials/header.php',
    dirname(ROOT_PATH) . '/header.php',
];

foreach ($header_candidates as $hf) {
    if (file_exists($hf) && is_readable($hf)) {
        include $hf;
        $headerIncluded = true;
        echo "<!-- HEADER_AUTO_INCLUDED: " . htmlspecialchars($hf) . " -->\n";
        break;
    }
}

// If none found, show visible fallback header so user can still use the app
if (!$headerIncluded) {
    echo "<!-- HEADER_AUTO_INCLUDED: none found -->\n";
    echo "<!doctype html><html><head><meta charset='utf-8'><title>App (fallback)</title>";
    if (file_exists(ROOT_PATH . '/assets/css/style.css')) {
        echo "<link rel='stylesheet' href='assets/css/style.css'>";
    } else {
        echo '<style>body{font-family:Arial,Helvetica,sans-serif;background:#f6f6f6;margin:0;padding:20px;} .page-box{max-width:980px;margin:20px auto;padding:20px;background:#fff;border-radius:8px;}</style>';
    }
    echo "</head><body>";
    echo '<div style="background:#dfffe8;padding:12px;text-align:center;color:#1f7a2f;font-weight:700;">FALLBACK HEADER (header.php not found)</div>';
    echo "<div class='page-box'>"; // open container for views
}

// ---------- routing: choose view ----------
$p = null;
switch ($page) {
    case 'dashboard':
        $p = VIEWS_PATH . '/dashboard.php';
        break;
    case 'login':
        $p = VIEWS_PATH . '/modules/auth/login.php';
        break;
    case 'logout':
        $p = VIEWS_PATH . '/modules/auth/logout.php';
        break;
    case 'list':
        $p = VIEWS_PATH . '/modules/user/list.php';
        break;
    case 'tambah':
        $p = VIEWS_PATH . '/modules/user/tambah.php';
        break;
    case 'ubah':
        $p = VIEWS_PATH . '/modules/user/ubah.php';
        break;
    case 'hapus':
        $p = VIEWS_PATH . '/modules/user/hapus.php';
        break;
    default:
        $p = null;
        echo "<div class='content'><h2>404 - Halaman tidak ditemukan</h2></div>";
        break;
}

// ---------- include the view (if exists) ----------
if (!empty($p)) {
    if (file_exists($p) && is_readable($p)) {
        include $p;
    } else {
        echo "<div class='content'><h2>Halaman belum tersedia: " . htmlspecialchars($p) . "</h2></div>";
    }
}

// ---------- intelligent footer include (search common locations) ----------
$footerIncluded = false;
$footer_candidates = [
    ROOT_PATH . '/footer.php',
    ROOT_PATH . '/views/footer.php',
    ROOT_PATH . '/includes/footer.php',
    ROOT_PATH . '/partials/footer.php',
    dirname(ROOT_PATH) . '/footer.php',
];

foreach ($footer_candidates as $ff) {
    if (file_exists($ff) && is_readable($ff)) {
        include $ff;
        $footerIncluded = true;
        echo "<!-- FOOTER_AUTO_INCLUDED: " . htmlspecialchars($ff) . " -->\n";
        break;
    }
}

// If footer not found, close page-box and print fallback footer
if (!$footerIncluded) {
    echo "</div> <!-- end .page-box (fallback) -->\n";
    echo '<footer class="app-footer" style="margin-top:40px;padding:16px;text-align:center;background:#e9f9ee;border-top:1px solid #d3ecd8;color:#2d6b3a;font-size:14px;border-radius:10px;">';
    echo '<div class="container"><small>Praktikum 9 - Modular • Universitas Pelita Bangsa &copy; '.date('Y').'</small></div>';
    echo '</footer>';
    echo '<style>html,body{height:100%} .app-footer{box-shadow:0 8px 20px rgba(0,0,0,0.03);}</style>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>';
    echo "</body></html>";
}
